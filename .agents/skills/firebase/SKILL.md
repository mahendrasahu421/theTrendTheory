---
name: firebase-ecommerce
description: Specialized skill for managing Firebase services (Firebase Authentication, Firestore Database, Cloud Storage) for The Trend Theory ecommerce platform.
---

# Firebase E-Commerce Agent Skill (The Trend Theory)

This skill provides step-by-step guidance, code patterns, and reference architectures for using **Firebase as the backend** (Authentication and Firestore Database) for The Trend Theory e-commerce platform.

## 1. Project Configuration

- **Firebase Project ID**: `the-trend-theory`
- **Firebase Web App ID**: `1:664156075505:web:3b83c116e07b428bef0050`
- **Sender ID**: `664156075505`
- **Auth Domain**: `the-trend-theory.firebaseapp.com`
- **Storage Bucket**: `the-trend-theory.firebasestorage.app`

### Web SDK Credentials Object
```javascript
const firebaseConfig = {
  apiKey: "AIzaSyBxewN-r_TDJfHBwuzcdIq2Bme6dyRCWVo",
  authDomain: "the-trend-theory.firebaseapp.com",
  projectId: "the-trend-theory",
  storageBucket: "the-trend-theory.firebasestorage.app",
  messagingSenderId: "664156075505",
  appId: "1:664156075505:web:3b83c116e07b428bef0050"
};
```

---

## 2. Firebase Authentication Workflows

### A. Email & Password Authentication
```javascript
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword } from "firebase/auth";

const auth = getAuth();

// Sign Up
async function signUpWithEmail(email, password) {
  const userCredential = await createUserWithEmailAndPassword(auth, email, password);
  return userCredential.user;
}

// Sign In
async function signInWithEmail(email, password) {
  const userCredential = await signInWithEmailAndPassword(auth, email, password);
  return userCredential.user;
}
```

### B. Google Sign-In
```javascript
import { getAuth, GoogleAuthProvider, signInWithPopup } from "firebase/auth";

const auth = getAuth();
const googleProvider = new GoogleAuthProvider();
googleProvider.setCustomParameters({ prompt: 'select_account' });

async function signInWithGoogle() {
  const result = await signInWithPopup(auth, googleProvider);
  return result.user;
}
```

### C. Phone Number OTP Authentication
```javascript
import { getAuth, RecaptchaVerifier, signInWithPhoneNumber } from "firebase/auth";

const auth = getAuth();

function setupRecaptcha(containerId = 'recaptcha-container') {
  window.recaptchaVerifier = new RecaptchaVerifier(auth, containerId, {
    size: 'invisible',
    callback: () => {}
  });
}

async function sendPhoneOtp(phoneNumber) {
  setupRecaptcha();
  const confirmationResult = await signInWithPhoneNumber(auth, phoneNumber, window.recaptchaVerifier);
  window.confirmationResult = confirmationResult;
  return confirmationResult;
}

async function verifyPhoneOtp(otpCode) {
  const result = await window.confirmationResult.confirm(otpCode);
  return result.user;
}
```

---

## 3. Cloud Firestore Database Schema for Ecommerce

Firestore collections and document modeling:

### Collection: `users`
- **Document ID**: `uid` (Firebase Auth UID)
- **Fields**:
  - `uid`: string
  - `name`: string
  - `email`: string
  - `phone`: string (optional)
  - `role`: string ('customer' | 'admin' | 'staff')
  - `photoURL`: string
  - `createdAt`: timestamp
  - `addresses`: array of address objects `{ name, phone, address, city, state, pincode, isDefault }`

### Collection: `products`
- **Document ID**: `productId` or slug
- **Fields**:
  - `name`: string
  - `slug`: string
  - `sku`: string
  - `price`: number
  - `salePrice`: number (nullable)
  - `categories`: array of strings
  - `images`: array of image URLs
  - `variants`: array of `{ size, color, stock, sku }`
  - `inStock`: boolean
  - `isActive`: boolean
  - `updatedAt`: timestamp

### Collection: `orders`
- **Document ID**: `orderId` (e.g. `TTT-ORD-XXXX`)
- **Fields**:
  - `userId`: string (uid)
  - `customerEmail`: string
  - `customerPhone`: string
  - `shippingAddress`: map
  - `items`: array of `{ productId, name, size, color, quantity, price, image }`
  - `subtotal`: number
  - `discount`: number
  - `tax`: number
  - `shippingCost`: number
  - `total`: number
  - `paymentStatus`: 'pending' | 'paid' | 'failed'
  - `paymentMethod`: 'razorpay' | 'phonepe' | 'cod'
  - `orderStatus`: 'placed' | 'confirmed' | 'shipped' | 'delivered' | 'cancelled'
  - `createdAt`: timestamp

### Collection: `carts`
- **Document ID**: `uid`
- **Fields**:
  - `items`: array of cart item objects
  - `updatedAt`: timestamp

---

## 4. Frontend-to-Backend Hybrid Session Synchronization

To keep Laravel's existing server-side checkout, order invoice generation, and admin dashboard functioning seamlessly with Firebase Auth, the frontend bridges Firebase credentials to Laravel via `/api/firebase/auth/sync`:

```javascript
async function syncFirebaseWithLaravel(user) {
  const token = await user.getIdToken();
  const response = await fetch('/api/firebase/auth/sync', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
      idToken: token,
      uid: user.uid,
      email: user.email,
      displayName: user.displayName,
      phoneNumber: user.phoneNumber,
      photoURL: user.photoURL
    })
  });
  return await response.json();
}
```

---

## 5. Security Rules for Firestore

```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    match /users/{userId} {
      allow read, write: if request.auth != null && request.auth.uid == userId;
    }
    match /products/{productId} {
      allow read: if true;
      allow write: if request.auth != null && request.auth.token.role == 'admin';
    }
    match /orders/{orderId} {
      allow read: if request.auth != null && (resource.data.userId == request.auth.uid || request.auth.token.role == 'admin');
      allow create: if request.auth != null;
      allow update, delete: if request.auth != null && request.auth.token.role == 'admin';
    }
    match /carts/{userId} {
      allow read, write: if request.auth != null && request.auth.uid == userId;
    }
  }
}
```
