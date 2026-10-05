/**
 * THE TREND THEORY — Firebase E-Commerce Integration Module
 * Firebase Project: the-trend-theory
 * Web App ID: 1:664156075505:web:3b83c116e07b428bef0050
 * Services: Firebase Authentication & Cloud Firestore Database
 */

(function (window) {
    'use strict';

    const FIREBASE_CONFIG = {
        apiKey: "AIzaSyBxewN-r_TDJfHBwuzcdIq2Bme6dyRCWVo",
        authDomain: "the-trend-theory.firebaseapp.com",
        projectId: "the-trend-theory",
        storageBucket: "the-trend-theory.firebasestorage.app",
        messagingSenderId: "664156075505",
        appId: "1:664156075505:web:3b83c116e07b428bef0050"
    };

    let app = null;
    let auth = null;
    let db = null;
    let recaptchaVerifier = null;
    let confirmationResult = null;

    // Initialize Firebase
    function init() {
        if (typeof firebase === 'undefined') {
            console.warn('Firebase SDK scripts not loaded yet.');
            return false;
        }

        if (!firebase.apps.length) {
            app = firebase.initializeApp(FIREBASE_CONFIG);
        } else {
            app = firebase.app();
        }

        auth = firebase.auth();
        db = firebase.firestore();
        console.log('Firebase initialized for The Trend Theory (' + FIREBASE_CONFIG.projectId + ')');
        return true;
    }

    // Helper: Sync Firebase session to Laravel backend
    async function syncBackend(user) {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        const payload = {
            uid: user.uid,
            email: user.email || '',
            displayName: user.displayName || '',
            phoneNumber: user.phoneNumber || '',
            photoURL: user.photoURL || ''
        };

        const res = await fetch('/api/firebase/auth/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        return await res.json();
    }

    // Helper: Save user profile to Cloud Firestore
    async function saveUserToFirestore(user, extra = {}) {
        if (!db) init();
        try {
            const userRef = db.collection('users').doc(user.uid);
            const data = {
                uid: user.uid,
                name: user.displayName || extra.name || 'Trend Member',
                email: user.email || extra.email || '',
                phone: user.phoneNumber || extra.phone || '',
                photoURL: user.photoURL || '',
                role: 'customer',
                updatedAt: firebase.firestore.FieldValue.serverTimestamp(),
                ...extra
            };

            await userRef.set(data, { merge: true });
            console.log('Firestore: User profile saved for ' + user.uid);
        } catch (err) {
            console.warn('Firestore write warning:', err);
        }
    }

    // 1. EMAIL & PASSWORD REGISTRATION
    async function signUpWithEmail(email, password, displayName = '', phone = '') {
        if (!auth) init();
        const cred = await auth.createUserWithEmailAndPassword(email, password);
        const user = cred.user;

        if (displayName) {
            await user.updateProfile({ displayName: displayName });
        }

        await saveUserToFirestore(user, { name: displayName, phone: phone });
        const syncResult = await syncBackend(user);
        return { user, syncResult };
    }

    // 2. EMAIL & PASSWORD LOGIN
    async function signInWithEmail(email, password) {
        if (!auth) init();
        const cred = await auth.signInWithEmailAndPassword(email, password);
        const user = cred.user;

        await saveUserToFirestore(user);
        const syncResult = await syncBackend(user);
        return { user, syncResult };
    }

    // 3. GOOGLE SIGN-IN (Popup + Redirect fallback)
    async function signInWithGoogle() {
        if (!auth) init();
        const provider = new firebase.auth.GoogleAuthProvider();
        provider.setCustomParameters({ prompt: 'select_account' });
        provider.addScope('email');
        provider.addScope('profile');

        try {
            const result = await auth.signInWithPopup(provider);
            const user = result.user;

            await saveUserToFirestore(user);
            const syncResult = await syncBackend(user);
            return { user, syncResult };
        } catch (popupErr) {
            if (popupErr.code === 'auth/popup-blocked') {
                console.log('Firebase popup blocked, initiating redirect sign-in...');
                await auth.signInWithRedirect(provider);
                return { redirecting: true };
            }
            throw popupErr;
        }
    }

    // 3b. FACEBOOK SIGN-IN (Popup + Redirect fallback)
    async function signInWithFacebook() {
        if (!auth) init();
        const provider = new firebase.auth.FacebookAuthProvider();
        provider.addScope('email');
        provider.addScope('public_profile');

        try {
            const result = await auth.signInWithPopup(provider);
            const user = result.user;

            await saveUserToFirestore(user);
            const syncResult = await syncBackend(user);
            return { user, syncResult };
        } catch (popupErr) {
            if (popupErr.code === 'auth/popup-blocked') {
                console.log('Firebase popup blocked, initiating redirect sign-in...');
                await auth.signInWithRedirect(provider);
                return { redirecting: true };
            }
            throw popupErr;
        }
    }

    // Check for redirect result on page load
    async function checkRedirectResult() {
        if (!auth) init();
        try {
            const result = await auth.getRedirectResult();
            if (result && result.user) {
                await saveUserToFirestore(result.user);
                const syncResult = await syncBackend(result.user);
                if (syncResult && syncResult.success) {
                    window.location.href = syncResult.redirect || '/';
                    return true;
                }
            }
        } catch (e) {
            console.warn('Firebase redirect result:', e);
        }
        return false;
    }

    // 4. PHONE AUTHENTICATION (RECAPTCHA & SMS OTP)
    function setupPhoneRecaptcha(containerId = 'recaptcha-container') {
        if (!auth) init();
        if (recaptchaVerifier) {
            try { recaptchaVerifier.clear(); } catch(e){}
        }

        recaptchaVerifier = new firebase.auth.RecaptchaVerifier(containerId, {
            size: 'invisible',
            callback: function () {
                console.log('Recaptcha verified for Phone Auth');
            },
            'expired-callback': function () {
                console.warn('Recaptcha expired. Please try again.');
            }
        });

        return recaptchaVerifier;
    }

    async function sendPhoneOtp(phoneNumber, containerId = 'recaptcha-container') {
        if (!auth) init();
        const verifier = setupPhoneRecaptcha(containerId);

        // Format to E.164 if missing country code
        let formattedPhone = phoneNumber.trim();
        if (!formattedPhone.startsWith('+')) {
            // Default to India (+91) if 10 digits
            formattedPhone = formattedPhone.replace(/^0+/, '');
            formattedPhone = '+91' + formattedPhone;
        }

        confirmationResult = await auth.signInWithPhoneNumber(formattedPhone, verifier);
        window.confirmationResult = confirmationResult;
        return confirmationResult;
    }

    async function verifyPhoneOtp(otpCode) {
        if (!confirmationResult && window.confirmationResult) {
            confirmationResult = window.confirmationResult;
        }
        if (!confirmationResult) {
            throw new Error('No active OTP session found. Please request a new OTP code.');
        }

        const cred = await confirmationResult.confirm(otpCode);
        const user = cred.user;

        await saveUserToFirestore(user);
        const syncResult = await syncBackend(user);
        return { user, syncResult };
    }

    // 5. SIGN OUT
    async function signOut() {
        if (!auth) init();
        await auth.signOut();
    }

    // Auto-init on script load if SDK is ready
    if (typeof firebase !== 'undefined') {
        init();
        checkRedirectResult();
    }

    // Expose to global window object
    window.TrendFirebase = {
        init: init,
        config: FIREBASE_CONFIG,
        getAuth: () => auth,
        getFirestore: () => db,
        signUpWithEmail: signUpWithEmail,
        signInWithEmail: signInWithEmail,
        signInWithGoogle: signInWithGoogle,
        signInWithFacebook: signInWithFacebook,
        sendPhoneOtp: sendPhoneOtp,
        verifyPhoneOtp: verifyPhoneOtp,
        checkRedirectResult: checkRedirectResult,
        saveUserToFirestore: saveUserToFirestore,
        syncBackend: syncBackend,
        signOut: signOut
    };

})(window);
