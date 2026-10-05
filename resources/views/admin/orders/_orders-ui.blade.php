{{-- resources/views/admin/orders/_orders-ui.blade.php
     Shared design layer for admin/orders/index and admin/orders/show.
     Included by both pages after their own style block. Pure CSS: no markup, JS or route changes. --}}
<style>
/* ═════════ 1. Tokens ═════════ */
:root{
  --ot-navy:#00285a; --ot-navy-2:#0b3d86; --ot-blue:#2563eb; --ot-sky:#38bdf8;
  --ot-ink:#0f172a; --ot-muted:#64748b; --ot-line:#e2e8f0; --ot-soft:#f8fafc;
  --ot-shadow-1:0 1px 2px rgba(15,23,42,.04),0 6px 20px rgba(0,40,90,.06);
  --ot-shadow-2:0 2px 4px rgba(15,23,42,.05),0 16px 38px rgba(0,40,90,.12);
  --ot-ring:0 0 0 3px rgba(37,99,235,.22);
  --ot-ease:cubic-bezier(.16,1,.3,1);
}

/* ═════════ 2. Utility layer (layout does NOT load Bootstrap) ═════════ */
.d-flex{display:flex!important}.d-block{display:block!important}.d-inline-flex{display:inline-flex!important}
.flex-wrap{flex-wrap:wrap!important}.flex-shrink-0{flex-shrink:0!important}
.align-items-center{align-items:center!important}.align-items-start{align-items:flex-start!important}
.justify-content-between{justify-content:space-between!important}
.justify-content-center{justify-content:center!important}
.justify-content-end{justify-content:flex-end!important}
.gap-1{gap:4px!important}.gap-2{gap:8px!important}.gap-3{gap:16px!important}
.gap-1\.5{gap:6px!important}
.m-0,.mb-0{margin-bottom:0!important}.mb-1{margin-bottom:4px!important}.mb-2{margin-bottom:8px!important}.mb-3{margin-bottom:16px!important}
.mt-1{margin-top:4px!important}.mt-2{margin-top:8px!important}.mt-3{margin-top:16px!important}.mt-0\.5{margin-top:2px!important}
.me-1{margin-right:4px!important}.me-1\.5{margin-right:6px!important}.me-2{margin-right:8px!important}
.ms-1{margin-left:4px!important}.ms-2{margin-left:8px!important}
.p-0{padding:0!important}.p-2{padding:8px!important}
.px-1\.5{padding-left:6px!important;padding-right:6px!important}
.px-2{padding-left:8px!important;padding-right:8px!important}
.px-3{padding-left:16px!important;padding-right:16px!important}
.px-4{padding-left:24px!important;padding-right:24px!important}
.py-0\.5{padding-top:2px!important;padding-bottom:2px!important}
.py-1{padding-top:4px!important;padding-bottom:4px!important}
.py-1\.5{padding-top:6px!important;padding-bottom:6px!important}
.py-2{padding-top:8px!important;padding-bottom:8px!important}
.py-4{padding-top:24px!important;padding-bottom:24px!important}
.py-5{padding-top:48px!important;padding-bottom:48px!important}
.pb-2{padding-bottom:8px!important}.pt-3{padding-top:16px!important}
.fw-bold,.font-bold{font-weight:700!important}.fw-normal{font-weight:400!important}
.fs-5{font-size:1.15rem!important}.fs-6{font-size:1rem!important}
.font-xs{font-size:12px!important}.font-xxs{font-size:10.5px!important}
.text-center{text-align:center!important}.text-end{text-align:right!important}
.text-uppercase{text-transform:uppercase!important}
.text-truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.text-decoration-none{text-decoration:none!important}
.text-muted{color:#64748b!important}.text-secondary{color:#64748b!important}
.text-primary{color:#2563eb!important}.text-success{color:#059669!important}
.text-warning{color:#d97706!important}.text-danger{color:#dc2626!important}
.text-dark{color:#0f172a!important}.text-navy{color:#00285a!important}.text-purple{color:#7c3aed!important}
.bg-white{background:#fff!important}.bg-light{background:#f1f5f9!important}
.bg-primary-subtle{background:#dbeafe!important}.bg-warning-subtle{background:#fef3c7!important}
.bg-success-subtle{background:#d1fae5!important}
.border{border:1px solid #e2e8f0!important}.border-top{border-top:1px solid #e2e8f0!important}
.border-bottom{border-bottom:1px solid #e2e8f0!important}
.rounded{border-radius:8px!important}.opacity-75{opacity:.75}

.badge{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;line-height:1.2;
  padding:4px 9px;border-radius:7px;white-space:nowrap}
.row{display:flex;flex-wrap:wrap;gap:16px}.row.g-3{gap:16px}
.col-md-6{flex:1 1 calc(50% - 8px);min-width:220px}.col-md-7{flex:0 1 58%;min-width:260px}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;font-family:inherit;font-weight:700;
  line-height:1.2;border:1px solid transparent;cursor:pointer;text-decoration:none;
  transition:all .18s var(--ot-ease)}
.btn-sm{padding:8px 14px;font-size:12px;border-radius:10px}
.btn:disabled{opacity:.6;cursor:not-allowed}
.btn:hover:not(:disabled){transform:translateY(-1px)}
.btn-primary{background:var(--ot-blue);color:#fff}.btn-primary:hover{background:#1d4ed8;color:#fff}
.btn-success{background:#059669;color:#fff}.btn-success:hover{background:#047857;color:#fff}
.btn-danger{background:#dc2626;color:#fff}
.btn-light{background:#fff;border-color:var(--ot-line);color:#334155}
.btn-light:hover{background:#f8fafc;border-color:#cbd5e1;color:var(--ot-ink)}
.btn-outline-secondary{background:#fff;border-color:#cbd5e1;color:#475569}
.btn-link{background:none;color:var(--ot-blue);border:0;padding:0}

.spinner-border{display:inline-block;width:2rem;height:2rem;border:.22em solid currentColor;
  border-right-color:transparent;border-radius:50%;animation:otSpin .7s linear infinite}
.spinner-border-sm{width:1em;height:1em;border-width:.16em}
@keyframes otSpin{to{transform:rotate(360deg)}}
.visually-hidden{position:absolute!important;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap}

/* undefined-but-used classes */
.studio-alert-banner.alert-cancelled{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c}
.studio-alert-banner.alert-refunded{background:#f8fafc;border:1px solid #cbd5e1;color:#475569}
.quick-chip-group{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px}
.quick-courier-chip{background:#f8fafc;border:1px solid #cbd5e1;color:#334155;font-size:11.5px;font-weight:700;
  padding:5px 12px;border-radius:8px;cursor:pointer;user-select:none;transition:all .15s}
.quick-courier-chip:hover{border-color:var(--ot-blue);color:var(--ot-blue);background:#eff6ff;transform:translateY(-1px)}

/* ═════════ 3. Hero header (index toolbar + show banner) ═════════ */
.orders-toolbar,.order-hero-banner{
  position:relative;overflow:hidden;color:#fff;border:0;border-radius:20px;
  background:linear-gradient(120deg,var(--ot-navy) 0%,var(--ot-navy-2) 58%,#1d4ed8 100%);
  box-shadow:0 14px 36px rgba(0,40,90,.28)}
.orders-toolbar{padding:24px 28px}
.order-hero-banner{padding:26px 30px}
.orders-toolbar::before,.order-hero-banner::before{content:"";position:absolute;inset:0;pointer-events:none;
  background:radial-gradient(500px 220px at 92% -10%,rgba(56,189,248,.35),transparent 70%),
             radial-gradient(360px 200px at 0% 120%,rgba(255,255,255,.10),transparent 70%)}
.orders-toolbar>*,.order-hero-banner>*{position:relative;z-index:1}
.orders-title,.order-hero-title{color:#fff;letter-spacing:-.5px}
.orders-subtitle,.order-hero-desc{color:rgba(255,255,255,.72)}
.order-hero-desc{font-size:13px;margin:8px 0 0;line-height:1.55}
.order-hero-desc strong{color:#fff}
.orders-live-indicator,.order-hero-badge{display:inline-flex;align-items:center;gap:7px;font-size:10.5px;font-weight:800;
  letter-spacing:.7px;padding:4px 12px;border-radius:999px;color:#fff;
  background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22)}
.orders-pulse-dot,.order-pulse-dot{width:7px;height:7px;border-radius:50%;background:#34d399;
  box-shadow:0 0 0 3px rgba(52,211,153,.3);animation:pulseLive 2s infinite}
.order-hero-badge{margin-bottom:12px}
.order-hero-title{display:flex;align-items:center;gap:12px;flex-wrap:wrap;font-size:27px;font-weight:800;margin:0}
.order-hero-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}

/* glass buttons on the hero */
.btn-orders-action,.btn-hero-back,.order-hero-actions .btn-light{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;height:42px;padding:0 16px;border-radius:12px;
  font-size:12.5px;font-weight:700;text-decoration:none;cursor:pointer;color:#fff!important;
  background:rgba(255,255,255,.12)!important;border:1px solid rgba(255,255,255,.26)!important;
  box-shadow:none;backdrop-filter:blur(6px);transition:all .18s var(--ot-ease)}
.btn-orders-action:hover,.btn-hero-back:hover,.order-hero-actions .btn-light:hover{
  background:rgba(255,255,255,.22)!important;transform:translateY(-1px);color:#fff!important}
.btn-orders-action .text-primary,.order-hero-actions .text-danger,.order-hero-actions .text-warning{color:#bfdbfe!important}
.btn-orders-action.active-guide{background:rgba(255,255,255,.26)!important;border-color:#fff!important}
.btn-orders-scanner{background:rgba(16,185,129,.22)!important;border-color:rgba(52,211,153,.55)!important;color:#d1fae5!important}
.btn-orders-print,.order-hero-actions .btn-success{background:#fff!important;border-color:#fff!important;color:var(--ot-navy)!important}
.btn-orders-print:hover{background:#eff6ff!important;color:var(--ot-navy)!important}
.order-hero-actions .btn-success{background:#10b981!important;border-color:#10b981!important;color:#fff!important;
  height:42px;padding:0 16px;border-radius:12px!important}
.order-hero-title span[style]{background:rgba(255,255,255,.14)!important;color:#fff!important;border-color:rgba(255,255,255,.3)!important}
.btn-orders-action:focus-visible,.btn-hero-back:focus-visible,.status-chip:focus-visible,.btn-page-nav:focus-visible,
.action-btn:focus-visible,.btn-filter-reset:focus-visible,.btn-update-dispatch:focus-visible{outline:none;box-shadow:var(--ot-ring)}

/* ═════════ 4. KPI / bento cards (index metrics + show bento) ═════════ */
.orders-metric,.bento-order-card{position:relative;overflow:hidden;background:#fff;border:1px solid var(--ot-line);
  border-radius:16px;box-shadow:var(--ot-shadow-1);transition:all .22s var(--ot-ease)}
.orders-metric::before,.bento-order-card::before{content:"";position:absolute;left:0;top:14px;bottom:14px;width:3px;
  border-radius:0 3px 3px 0;background:var(--ot-blue);opacity:0;transform:scaleY(.4);transition:all .25s var(--ot-ease)}
.orders-metric:hover,.bento-order-card:hover{transform:translateY(-2px);box-shadow:var(--ot-shadow-2)}
.orders-metric:hover::before,.orders-metric.active-metric-filter::before,.bento-order-card:hover::before{opacity:1;transform:scaleY(1)}
.orders-metric.active-metric-filter{border-color:var(--ot-blue);background:#f8faff}
.metric-value,.bento-num{font-variant-numeric:tabular-nums;letter-spacing:-.5px}
.metric-value{font-size:24px}
.metric-label .bi-filter{opacity:.3;transition:opacity .2s}
.orders-metric:hover .metric-label .bi-filter{opacity:1}

.bento-kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.bento-order-card{padding:18px 20px}
.bento-card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.bento-card-label{font-size:10.5px;font-weight:800;color:var(--ot-muted);text-transform:uppercase;letter-spacing:.7px}
.bento-icon-box{width:36px;height:36px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:17px}
.bento-num{font-size:26px;font-weight:800;color:var(--ot-navy);line-height:1.1}
.bento-sub{margin-top:6px;font-size:12px;color:var(--ot-muted)}

/* ═════════ 5. INDEX: card, filters, table ═════════ */
.orders-card{border:1px solid var(--ot-line);border-radius:18px;box-shadow:var(--ot-shadow-1)}
.orders-card-head{background:linear-gradient(180deg,#fff,#fbfcfe)}
.order-search input,.orders-filter select{border-width:1px;background-color:#fbfcfe}
.order-search input:hover,.orders-filter select:hover{border-color:#cbd5e1;background-color:#fff}
.order-search input:focus,.orders-filter select:focus{box-shadow:var(--ot-ring)}
.status-strip{gap:6px;padding:10px 24px;background:#fbfcfe;scrollbar-width:none}
.status-strip::-webkit-scrollbar{display:none}
.status-chip{border-width:1px}
.status-chip[data-status="pending"] .chip-count{background:#fef3c7;color:#b45309}
.status-chip[data-status="confirmed"] .chip-count{background:#dbeafe;color:#1d4ed8}
.status-chip[data-status="processing"] .chip-count{background:#e0f2fe;color:#0369a1}
.status-chip[data-status="shipped"] .chip-count{background:#ede9fe;color:#6d28d9}
.status-chip[data-status="delivered"] .chip-count{background:#d1fae5;color:#047857}
.status-chip[data-status="cancelled"] .chip-count{background:#fee2e2;color:#b91c1c}
.status-chip.active .chip-count{background:rgba(255,255,255,.22)!important;color:#fff!important}

.orders-table-wrap{max-height:68vh;overflow:auto;scrollbar-width:thin}
.orders-table{border-collapse:separate;border-spacing:0;min-width:1080px}
.orders-table thead th{position:sticky;top:0;z-index:3;background:rgba(248,250,252,.94);backdrop-filter:blur(8px);
  border-bottom:1px solid var(--ot-line);font-size:10.5px;letter-spacing:.8px;color:var(--ot-muted)}
.orders-table tbody tr:nth-child(even) td{background:#fcfdff}
.orders-table tbody tr:hover td{background:#f3f7ff}
.orders-table tbody tr.is-selected td{background:#eaf2ff}
.orders-table tbody tr:last-child td{border-bottom:0}
.orders-table tbody td:first-child{box-shadow:inset 3px 0 0 transparent}
.orders-table tbody tr:has(.status-pending) td:first-child{box-shadow:inset 3px 0 0 #f59e0b}
.orders-table tbody tr:has(.status-confirmed) td:first-child{box-shadow:inset 3px 0 0 #3b82f6}
.orders-table tbody tr:has(.status-processing) td:first-child{box-shadow:inset 3px 0 0 #0ea5e9}
.orders-table tbody tr:has(.status-shipped) td:first-child{box-shadow:inset 3px 0 0 #8b5cf6}
.orders-table tbody tr:has(.status-delivered) td:first-child{box-shadow:inset 3px 0 0 #10b981}
.orders-table tbody tr:has(.status-cancelled) td:first-child{box-shadow:inset 3px 0 0 #ef4444}
.order-number-link,.amount-cell{font-variant-numeric:tabular-nums}
.order-prod-thumb{border-radius:12px;border:1px solid var(--ot-line);transition:transform .2s var(--ot-ease)}
tr:hover .order-prod-thumb{transform:scale(1.06)}
.customer-avatar,.studio-cust-avatar,.customer-avatar-square{box-shadow:0 0 0 3px #fff,0 2px 8px rgba(0,40,90,.2)}
.status-pill,.badge-clearance{box-shadow:0 1px 2px rgba(15,23,42,.05)}
.status-pill:hover,.badge-clearance:hover{transform:translateY(-1px)}
@media (hover:hover){
  .orders-table .action-group{opacity:.55;transition:opacity .2s}
  .orders-table tbody tr:hover .action-group{opacity:1}}
.action-btn{border-width:1px;width:32px;height:32px}
.table-loading-overlay{background:rgba(255,255,255,.55);backdrop-filter:blur(1px);align-items:flex-start}
.table-loading-overlay .spinner-border{display:none}
.table-loading-overlay::before{content:"";position:absolute;top:0;left:0;height:3px;width:40%;
  background:linear-gradient(90deg,transparent,var(--ot-blue),var(--ot-sky),transparent);animation:otBar 1.1s infinite var(--ot-ease)}
@keyframes otBar{from{transform:translateX(-100%)}to{transform:translateX(260%)}}
.empty-state>i{display:inline-flex;width:84px;height:84px;align-items:center;justify-content:center;
  border-radius:50%;background:radial-gradient(circle,#eff6ff,#f8fafc);color:#94a3b8!important}
.table-footer-bar{background:#fbfcfe}
.btn-page-nav{border-width:1px;border-radius:9px;font-variant-numeric:tabular-nums}
.floating-bulk-bar{background:rgba(0,40,90,.94);backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.12);padding:10px 14px 10px 22px}

/* ═════════ 6. INDEX: modals ═════════ */
.custom-modal-overlay{background:rgba(9,20,45,.6)}
.custom-modal-card{border-radius:22px;box-shadow:0 40px 90px -20px rgba(0,30,80,.45)}
.modal-stepper-wrap,.studio-bento-card,.studio-control-card{border-width:1px;box-shadow:var(--ot-shadow-1)}
.studio-bento-card:hover,.studio-control-card:hover{box-shadow:var(--ot-shadow-2)}
.modal-flow-node.active{box-shadow:0 8px 22px rgba(37,99,235,.32)}
.modal-flow-node.active .node-icon-wrap{animation:otPop .4s var(--ot-ease)}
@keyframes otPop{from{transform:scale(.6)}to{transform:scale(1)}}
.form-control-modal{border-width:1px;background:#fbfcfe}
.form-control-modal:hover{border-color:#94a3b8;background:#fff}
.form-control-modal:focus{box-shadow:var(--ot-ring);background:#fff}
.custom-modal-footer{position:sticky;bottom:0;z-index:2}
.scanner-gun-input:focus{box-shadow:var(--ot-ring)}
.scanned-order-card{box-shadow:var(--ot-shadow-2)}
.custom-toast{border-radius:14px;backdrop-filter:blur(8px);box-shadow:var(--ot-shadow-2)}

/* ═════════ 7. SHOW: page frame ═════════ */
.order-studio-wrap{font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;
  display:flex;flex-direction:column;gap:20px;color:var(--ot-ink)}

/* lifecycle stepper */
.stepper-card-luxury{background:#fff;border:1px solid var(--ot-line);border-radius:18px;box-shadow:var(--ot-shadow-1);
  padding:28px 24px 22px;overflow-x:auto;scrollbar-width:none}
.stepper-card-luxury::-webkit-scrollbar{display:none}
.stepper-track-wrap{position:relative;display:flex;min-width:540px}
.stepper-bg-line{position:absolute;left:10%;right:10%;top:22px;height:4px;border-radius:99px;background:#e2e8f0;z-index:0}
.stepper-fill-line{height:100%;border-radius:99px;background:linear-gradient(90deg,#10b981,var(--ot-blue));
  transition:width .6s var(--ot-ease)}
.stepper-node-item{position:relative;z-index:1;flex:1 1 0;min-width:96px;display:flex;flex-direction:column;
  align-items:center;gap:10px;text-align:center}
.stepper-circle-icon{width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-size:19px;background:#fff;border:2px solid #e2e8f0;color:#94a3b8;transition:all .3s var(--ot-ease)}
.stepper-node-label{font-size:11.5px;font-weight:700;line-height:1.25;max-width:100px;color:#94a3b8}
.stepper-node-item.completed .stepper-circle-icon{background:#10b981;border-color:#10b981;color:#fff}
.stepper-node-item.completed .stepper-node-label{color:#047857}
.stepper-node-item.active .stepper-circle-icon{background:linear-gradient(135deg,var(--ot-navy),var(--ot-blue));
  border-color:transparent;color:#fff;box-shadow:0 0 0 6px rgba(37,99,235,.14);animation:otGlow 2.2s infinite}
.stepper-node-item.active .stepper-node-label{color:var(--ot-navy);font-weight:800}
@keyframes otGlow{50%{box-shadow:0 0 0 10px rgba(37,99,235,.08)}}

/* two-column layout */
.order-details-grid{display:grid;grid-template-columns:minmax(0,1fr) 390px;gap:20px;align-items:start}
.order-details-grid>div{display:flex;flex-direction:column;gap:20px;min-width:0}
@media (min-width:1081px){.order-details-grid>div:last-child{position:sticky;top:16px}}
@media (max-width:1080px){.order-details-grid{grid-template-columns:1fr}.bento-kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}

/* panels */
.studio-panel{background:#fff;border:1px solid var(--ot-line);border-radius:18px;box-shadow:var(--ot-shadow-1);overflow:hidden}
.studio-panel-head{padding:15px 22px;border-bottom:1px solid #eef2f7;background:linear-gradient(180deg,#fff,#fbfcfe)}
.studio-panel-title{margin:0;display:flex;align-items:center;gap:9px;font-size:12.5px;font-weight:800;
  text-transform:uppercase;letter-spacing:.7px;color:var(--ot-navy)}

/* products */
.showcase-product-row{display:flex;flex-wrap:wrap;gap:26px;padding:22px;border-bottom:1px solid #eef2f7}
.showcase-product-row:last-child{border-bottom:0}
.product-345-380-box{position:relative;flex:0 0 auto;width:345px;max-width:100%;aspect-ratio:345/380;border-radius:16px;
  overflow:hidden;background:#f1f5f9;border:1px solid var(--ot-line);box-shadow:0 10px 28px rgba(0,30,80,.12)}
.product-345-380-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .5s var(--ot-ease)}
.product-345-380-box:hover .product-345-380-img{transform:scale(1.04)}
.showcase-badge-floating{position:absolute;top:12px;left:12px;z-index:2;font-size:10.5px;font-weight:800;letter-spacing:.5px;
  color:#fff;padding:5px 11px;border-radius:999px;background:rgba(0,40,90,.82);backdrop-filter:blur(6px)}
.product-details-pane{flex:1 1 240px;min-width:0;display:flex;flex-direction:column;justify-content:space-between;gap:18px}
.product-title-large{margin:0;font-size:20px;font-weight:800;line-height:1.3;letter-spacing:-.2px;color:var(--ot-navy)}
.attr-pills-row{display:flex;flex-wrap:wrap;gap:8px;margin-top:14px}
.attr-pills-row .badge{padding:6px 11px!important;font-size:11.5px;font-weight:700;border-radius:8px}
.pricing-summary-cluster{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:16px 18px;border-radius:14px;
  background:linear-gradient(135deg,#f8fafc,#f1f5ff);border:1px solid var(--ot-line)}
.pricing-cluster-item{display:flex;flex-direction:column;gap:4px}
.pricing-cluster-label{font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:var(--ot-muted)}
.pricing-cluster-val{font-size:17px;font-weight:800;color:var(--ot-navy);font-variant-numeric:tabular-nums}

/* ledger */
.invoice-ledger-box{padding:18px 22px;background:#fbfcff;border-top:1px solid #eef2f7}
.ledger-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:13px;color:var(--ot-muted)}
.ledger-row strong{color:var(--ot-ink);font-variant-numeric:tabular-nums}
.ledger-total-highlight{display:flex;justify-content:space-between;align-items:center;margin-top:10px;padding-top:14px;
  border-top:1.5px dashed #cbd5e1}
.ledger-total-label{font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:#334155}
.ledger-total-amount{font-size:26px;font-weight:900;color:var(--ot-navy);letter-spacing:-.5px;font-variant-numeric:tabular-nums}

/* customer manifest */
.customer-manifest-container{display:flex;flex-direction:column;gap:18px;padding:22px}
.customer-header-row{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px}
.customer-info-left{display:flex;align-items:center;gap:14px;min-width:0}
.customer-avatar-square{width:52px;height:52px;border-radius:16px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
  font-size:20px;font-weight:800;color:#fff;background:linear-gradient(135deg,var(--ot-navy),var(--ot-blue))}
.customer-name-heading{margin:0;font-size:17px;font-weight:800;color:var(--ot-navy)}
.customer-phone-tag{display:inline-flex;align-items:center;gap:6px;margin-top:3px;font-size:13px;font-weight:600;color:var(--ot-muted)}
.customer-action-buttons{display:flex;gap:8px;flex-wrap:wrap}
.btn-contact-action{display:inline-flex;align-items:center;gap:7px;height:40px;padding:0 16px;border-radius:10px;
  font-size:12.5px;font-weight:700;text-decoration:none;border:1px solid var(--ot-line);cursor:pointer;
  transition:all .18s var(--ot-ease)}
.btn-contact-action:hover{transform:translateY(-1px)}
.btn-call-style{background:#fff;color:#334155}.btn-call-style:hover{background:#f8fafc;border-color:#cbd5e1}
.btn-whatsapp-style{background:linear-gradient(135deg,#25d366,#128c7e);border-color:transparent;color:#fff;
  box-shadow:0 4px 14px rgba(37,211,102,.3)}
.address-card-styled{padding:16px 18px;border-radius:14px;border:1px solid var(--ot-line);
  background:linear-gradient(180deg,#f8fafc,#f4f7fb)}
.address-card-label{display:flex;align-items:center;gap:7px;margin-bottom:8px;font-size:10.5px;font-weight:800;
  text-transform:uppercase;letter-spacing:.7px;color:var(--ot-muted)}
.address-card-body{font-size:14px;line-height:1.65;color:#1e293b}

/* fulfillment console */
.studio-panel-body{padding:22px 24px}
.form-group-modern{margin-bottom:16px}
.form-label-modern{display:block;margin-bottom:7px;font-size:11px;font-weight:800;text-transform:uppercase;
  letter-spacing:.6px;color:#475569}
.input-modern{width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:11px;background:#fbfcfe;
  font-family:inherit;font-size:13px;font-weight:600;color:var(--ot-ink);outline:none;transition:all .18s ease}
.input-modern:hover{border-color:#94a3b8;background:#fff}
.input-modern:focus{border-color:var(--ot-blue);box-shadow:var(--ot-ring);background:#fff}
.btn-update-dispatch{width:100%;height:48px;display:flex;align-items:center;justify-content:center;gap:8px;border:0;
  border-radius:12px;cursor:pointer;font-family:inherit;font-size:13.5px;font-weight:800;color:#fff;
  background:linear-gradient(135deg,var(--ot-navy),var(--ot-blue));box-shadow:0 8px 20px rgba(37,99,235,.3);
  transition:all .2s var(--ot-ease)}
.btn-update-dispatch:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(37,99,235,.38)}
.btn-update-dispatch:active{transform:translateY(0) scale(.99)}

/* payment widget */
.payment-intel-container{padding:20px 22px}
.payment-receipt-box{padding:16px 18px;border-radius:14px;box-shadow:0 1px 2px rgba(15,23,42,.04)}
.payment-receipt-cod{background:#fffbeb;border:1px solid #fde68a}
.payment-receipt-prepaid{background:#eff6ff;border:1px solid #bfdbfe}
.payment-row-item{display:flex;justify-content:space-between;align-items:center;padding:9px 0;font-size:13px;
  border-top:1px dashed rgba(15,23,42,.12)}
.payment-row-item:first-of-type{border-top:0}
.payment-row-item code{background:rgba(255,255,255,.7);padding:2px 7px;border-radius:6px;font-size:12px}

/* ═════════ 8. Responsive ═════════ */
@media (max-width:900px){
  .orders-toolbar,.order-hero-banner{padding:18px}
  .orders-toolbar-actions,.order-hero-actions{width:100%}
  .orders-toolbar-actions .btn-orders-action{flex:1 1 calc(50% - 10px)}
  .orders-filter{justify-content:flex-start;width:100%}
  .orders-filter select{flex:1 1 140px}
}
@media (max-width:700px){
  .order-hero-title,.orders-title{font-size:20px}
  .bento-kpi-grid{grid-template-columns:1fr 1fr}
  .bento-num{font-size:21px}
  .showcase-product-row{padding:16px;gap:16px}
  .product-345-380-box{width:100%}
  .pricing-summary-cluster{grid-template-columns:1fr 1fr}
  .stepper-card-luxury{padding:22px 14px 18px}
  .customer-action-buttons,.customer-action-buttons .btn-contact-action{width:100%;justify-content:center}
  .col-md-7{flex-basis:100%}
}
@media (max-width:680px){
  .floating-bulk-bar{left:12px;right:12px;transform:translateY(140px);border-radius:20px;flex-wrap:wrap;justify-content:center}
  .floating-bulk-bar.show{transform:translateY(0)}
  .custom-modal-overlay{padding:8px;align-items:flex-end}
  .custom-modal-card{max-height:96vh;border-radius:22px 22px 14px 14px}
  .custom-modal-header,.custom-modal-body,.custom-modal-footer{padding-left:16px;padding-right:16px}
}

/* ═════════ 9. Accessibility & print ═════════ */
@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}}
@media print{
  .floating-bulk-bar,.custom-toast,.orders-toolbar-actions,.order-hero-actions,
  .order-details-grid>div:last-child .studio-panel:first-child{display:none!important}
  .order-details-grid{grid-template-columns:1fr!important}
  .order-hero-banner{background:#fff!important;color:#000!important;box-shadow:none!important}
  .order-hero-title,.order-hero-desc,.order-hero-desc strong{color:#000!important}
  .studio-panel,.stepper-card-luxury,.bento-order-card{box-shadow:none!important;break-inside:avoid}
}
</style>
