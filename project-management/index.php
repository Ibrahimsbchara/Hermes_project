<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sprint Planner</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; min-height: 100vh; }

  /* NAV */
  nav {
    background: #fff; border-bottom: 1px solid #e5e7eb;
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 28px; position: sticky; top: 0; z-index: 100;
  }
  .nav-brand { font-size: 13px; font-weight: 700; color: #111827; letter-spacing: 0.04em; text-transform: uppercase; }
  .nav-tabs { display: flex; gap: 4px; }
  .nav-tab {
    padding: 7px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; color: #6b7280; border: 1px solid transparent; background: none;
    transition: all 0.15s; font-family: 'Segoe UI', sans-serif;
  }
  .nav-tab:hover { background: #f3f4f6; color: #374151; }
  .nav-tab.active { background: #111827; color: #fff; }

  /* VIEWS */
  .view { display: none; max-width: 900px; margin: 0 auto; padding: 32px 24px; }
  .view.active { display: block; }

  .page-heading { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; gap: 16px; }
  .page-heading-text h1 { font-size: 20px; font-weight: 700; color: #111827; }
  .page-heading-text p { font-size: 14px; color: #6b7280; margin-top: 4px; }

  /* BUTTONS */
  .btn-primary {
    background: #111827; color: #fff; border: none; border-radius: 8px;
    padding: 10px 20px; font-size: 13px; font-weight: 600; cursor: pointer;
    font-family: 'Segoe UI', sans-serif; transition: opacity 0.15s; white-space: nowrap; display: inline-flex; align-items: center; gap: 7px;
  }
  .btn-primary:hover { opacity: 0.85; }
  .btn-ghost {
    background: none; color: #6b7280; border: 1.5px solid #e5e7eb; border-radius: 8px;
    padding: 9px 18px; font-size: 13px; font-weight: 600; cursor: pointer;
    font-family: 'Segoe UI', sans-serif; transition: all 0.15s;
  }
  .btn-ghost:hover { border-color: #d1d5db; color: #374151; }
  .btn-danger {
    background: #fff; color: #ef4444; border: 1.5px solid #fca5a5; border-radius: 8px;
    padding: 9px 18px; font-size: 13px; font-weight: 600; cursor: pointer;
    font-family: 'Segoe UI', sans-serif; transition: all 0.15s;
  }
  .btn-danger:hover { background: #fef2f2; }

  /* TABLE */
  .table-wrap { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
  thead th { padding: 11px 16px; font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; text-align: left; white-space: nowrap; }
  tbody tr { border-bottom: 1px solid #f3f4f6; transition: background 0.12s; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #fafafa; }
  tbody td { padding: 13px 16px; font-size: 14px; color: #374151; vertical-align: middle; }
  .td-name { font-weight: 600; color: #111827; max-width: 220px; }
  .td-desc { color: #6b7280; font-size: 13px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .td-actions { display: flex; gap: 6px; align-items: center; }
  .tbl-btn {
    padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;
    border: 1.5px solid #e5e7eb; background: #fff; color: #6b7280; transition: all 0.13s; font-family: 'Segoe UI', sans-serif;
  }
  .tbl-btn:hover { border-color: #d1d5db; color: #374151; }
  .tbl-btn.del:hover { border-color: #fca5a5; color: #ef4444; background: #fef2f2; }

  /* PRIORITY BADGE */
  .badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 99px; letter-spacing: 0.03em; }
  .badge-low { background: #eff6ff; color: #3b82f6; }
  .badge-medium { background: #fffbeb; color: #d97706; }
  .badge-high { background: #fef2f2; color: #ef4444; }

  /* STATUS BADGE */
  .badge-pending { background: #fffbeb; color: #d97706; }
  .badge-accepted { background: #f0fdf4; color: #16a34a; }

  /* CAT CHIP */
  .cat-chip { display: inline-block; font-size: 11px; padding: 2px 8px; border-radius: 5px; background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }

  .empty-row td { text-align: center; padding: 48px 16px; color: #d1d5db; font-size: 14px; }

  /* POPUP / MODAL SHARED */
  .backdrop {
    position: fixed; inset: 0; background: rgba(0,0,0,0.32); z-index: 200;
    display: flex; align-items: center; justify-content: center; padding: 24px;
    opacity: 0; pointer-events: none; transition: opacity 0.18s;
  }
  .backdrop.open { opacity: 1; pointer-events: all; }
  .popup {
    background: #fff; border-radius: 14px; border: 1px solid #e5e7eb;
    width: 100%; max-width: 480px; padding: 28px; position: relative;
    box-shadow: 0 8px 40px rgba(0,0,0,0.12);
    transform: translateY(14px); transition: transform 0.2s;
  }
  .backdrop.open .popup { transform: translateY(0); }
  .popup-close {
    position: absolute; top: 16px; right: 16px; background: #f3f4f6;
    border: none; color: #6b7280; width: 28px; height: 28px; border-radius: 7px;
    cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: all 0.15s;
  }
  .popup-close:hover { background: #e5e7eb; color: #111827; }
  .popup-title { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 20px; padding-right: 32px; }

  /* FORM INSIDE POPUP */
  .form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
  label { font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
  input[type=text], input[type=number], textarea, select {
    border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 9px 13px;
    font-size: 14px; color: #111827; font-family: 'Segoe UI', sans-serif;
    outline: none; transition: border 0.18s; background: #fff; width: 100%;
  }
  input:focus, textarea:focus, select:focus { border-color: #111827; }
  textarea { resize: vertical; min-height: 72px; }
  select option { background: #fff; }

  .priority-row { display: flex; gap: 8px; }
  .priority-btn {
    flex: 1; padding: 9px 6px; border-radius: 7px; border: 1.5px solid #e5e7eb;
    background: #fff; color: #9ca3af; font-size: 12px; font-weight: 600;
    cursor: pointer; text-align: center; transition: all 0.15s;
  }
  .priority-btn.sel-low { background: #eff6ff; border-color: #93c5fd; color: #3b82f6; }
  .priority-btn.sel-medium { background: #fffbeb; border-color: #fcd34d; color: #d97706; }
  .priority-btn.sel-high { background: #fef2f2; border-color: #fca5a5; color: #ef4444; }

  .popup-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }

  /* DETAIL MODAL (read-only) */
  .modal-sec-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin-bottom: 6px; }
  .modal-desc-text { font-size: 14px; color: #374151; line-height: 1.65; margin-bottom: 20px; }
  .modal-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 22px; }
  .meta-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 14px; }
  .meta-key { font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
  .meta-val { font-size: 15px; font-weight: 700; color: #111827; }

  /* CONFIRM */
  .confirm-popup { max-width: 360px; }
  .confirm-msg { font-size: 14px; color: #6b7280; line-height: 1.55; margin-bottom: 22px; }

  /* DASHBOARD */
  .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 22px; margin-bottom: 22px; }
  .effort-card { background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 20px 22px; margin-bottom: 22px; }
  .effort-top { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; }
  .effort-title-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; }
  .effort-numbers { font-size: 22px; font-weight: 700; color: #111827; }
  .effort-numbers span { font-size: 13px; font-weight: 500; color: #9ca3af; }
  .bar-track { height: 10px; background: #e5e7eb; border-radius: 99px; overflow: hidden; }
  .bar-fill { height: 100%; border-radius: 99px; background: #111827; transition: width 0.4s ease, background 0.4s; }
  .bar-fill.warn { background: #f59e0b; }
  .bar-fill.over { background: #ef4444; }
  .bar-sub { display: flex; justify-content: space-between; margin-top: 8px; font-size: 12px; color: #9ca3af; }

  .lock-banner { display: flex; align-items: center; gap: 12px; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; }
  .lock-text { flex: 1; font-size: 13px; font-weight: 600; color: #16a34a; }
  .lock-sub { font-size: 12px; color: #4ade80; font-weight: 400; }

  .filter-bar { display: flex; gap: 7px; margin-bottom: 16px; flex-wrap: wrap; }
  .filter-chip {
    padding: 6px 14px; border-radius: 99px; font-size: 12px; font-weight: 600;
    border: 1.5px solid #e5e7eb; color: #9ca3af; cursor: pointer; background: #fff; transition: all 0.15s;
  }
  .filter-chip:hover { color: #374151; border-color: #d1d5db; }
  .filter-chip.active { background: #111827; color: #fff; border-color: #111827; }

  .section-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin-bottom: 12px; }

  .task-list { display: flex; flex-direction: column; gap: 9px; }
  .task-card {
    background: #fff; border: 1.5px solid #e5e7eb; border-radius: 12px;
    padding: 13px 16px; display: flex; align-items: center; gap: 13px;
    cursor: pointer; transition: border-color 0.15s, background 0.13s; position: relative;
  }
  .task-card:hover { border-color: #d1d5db; background: #fafafa; }
  .task-card.selected { border-color: #111827; background: #f9fafb; }
  .task-card.locked-card { cursor: default; }
  .task-card.locked-card:hover { border-color: #111827; background: #f9fafb; }
  .task-card.over-budget { opacity: 0.38; pointer-events: none; }

  .tc-check { width: 20px; height: 20px; border-radius: 6px; border: 1.5px solid #d1d5db; background: #fff; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
  .task-card.selected .tc-check { background: #111827; border-color: #111827; }
  .tc-check svg { display: none; }
  .task-card.selected .tc-check svg { display: block; }
  .tc-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
  .tc-dot.low { background: #93c5fd; }
  .tc-dot.medium { background: #fcd34d; }
  .tc-dot.high { background: #fca5a5; }
  .tc-body { flex: 1; min-width: 0; }
  .tc-name { font-size: 14px; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .tc-tag { display: inline-block; font-size: 11px; padding: 2px 8px; border-radius: 5px; background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; margin-top: 4px; }
  .tc-hrs { font-size: 13px; font-weight: 600; color: #6b7280; white-space: nowrap; flex-shrink: 0; }
  .tc-info { width: 28px; height: 28px; border-radius: 7px; border: 1.5px solid #e5e7eb; background: #fff; color: #9ca3af; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
  .tc-info:hover { border-color: #111827; color: #111827; }
  .over-label { position: absolute; right: 14px; top: 9px; font-size: 10px; font-weight: 700; color: #ef4444; text-transform: uppercase; letter-spacing: 0.06em; }
  .sprint-submit-row { margin-top: 18px; display: flex; justify-content: flex-end; }

  /* SETTINGS */
  .settings-block { margin-bottom: 8px; }
  .settings-block-title { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 4px; }
  .settings-block-desc { font-size: 13px; color: #6b7280; margin-bottom: 14px; line-height: 1.5; }
  .settings-row { display: flex; gap: 10px; align-items: center; }
  .divider { border: none; border-top: 1px solid #e5e7eb; margin: 22px 0; }

  /* CATEGORY MANAGER */
  .cat-list { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; min-height: 36px; }
  .cat-item {
    display: inline-flex; align-items: center; gap: 6px; background: #f3f4f6; border: 1px solid #e5e7eb;
    border-radius: 7px; padding: 5px 10px; font-size: 13px; color: #374151; font-weight: 500;
  }
  .cat-remove { background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 14px; line-height: 1; padding: 0 2px; transition: color 0.13s; }
  .cat-remove:hover { color: #ef4444; }
  .cat-add-row { display: flex; gap: 8px; }
  .cat-add-row input { flex: 1; }

  /* TOAST */
  .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(10px); background: #111827; color: #fff; border-radius: 9px; padding: 11px 20px; font-size: 13px; font-weight: 500; opacity: 0; transition: all 0.22s; z-index: 500; white-space: nowrap; }
  .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

  @media (max-width: 600px) {
    nav { padding: 12px 14px; }
    .nav-tab { padding: 6px 11px; font-size: 12px; }
    .view { padding: 22px 14px; }
    table { font-size: 13px; }
    thead th, tbody td { padding: 10px 10px; }
    .td-desc { display: none; }
  }
</style>
</head>
<body>

<nav>
  <span class="nav-brand">Sprint Planner</span>
  <div class="nav-tabs">
    <button class="nav-tab active" onclick="showView('tasks')">Tasks</button>
    <button class="nav-tab" onclick="showView('dashboard')">Manager Dashboard</button>
    <button class="nav-tab" onclick="showView('settings')">Settings</button>
  </div>
</nav>

<!-- ===== TASKS VIEW ===== -->
<div class="view active" id="view-tasks">
  <div class="page-heading">
    <div class="page-heading-text">
      <h1>Tasks</h1>
      <p>All submitted tasks. Click a row to view details, or use the actions to edit or delete.</p>
    </div>
    <button class="btn-primary" onclick="openCreatePopup()">+ Create Task</button>
  </div>
  <div class="table-wrap">
    <table id="tasks-table">
      <thead>
        <tr>
          <th>Task Name</th>
          <th>Description</th>
          <th>Category</th>
          <th>Priority</th>
          <th>Effort</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="tasks-tbody">
        <tr class="empty-row"><td colspan="7">Loading...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<!-- ===== DASHBOARD VIEW ===== -->
<div class="view" id="view-dashboard">
  <div class="page-heading">
    <div class="page-heading-text">
      <h1>Manager Dashboard</h1>
      <p>Select tasks for the sprint, then lock it in.</p>
    </div>
  </div>

  <div class="effort-card">
    <div class="effort-top">
      <span class="effort-title-label">Effort Remaining</span>
      <span class="effort-numbers" id="d-remaining">70 <span>/ 70 hrs</span></span>
    </div>
    <div class="bar-track"><div class="bar-fill" id="d-bar" style="width:0%"></div></div>
    <div class="bar-sub">
      <span id="d-used">0 hrs used</span>
      <span id="d-pct">0% of capacity</span>
    </div>
  </div>

  <div id="lock-banner" style="display:none" class="lock-banner">
    <span style="font-size:16px">&#128274;</span>
    <div>
      <div class="lock-text">Sprint is locked</div>
      <div class="lock-sub">Go to Settings to unlock or clear the sprint.</div>
    </div>
  </div>

  <div class="filter-bar">
    <button class="filter-chip active" data-f="all" onclick="setFilter('all')">All</button>
    <button class="filter-chip" data-f="high" onclick="setFilter('high')">High Priority</button>
    <button class="filter-chip" data-f="medium" onclick="setFilter('medium')">Medium</button>
    <button class="filter-chip" data-f="low" onclick="setFilter('low')">Low</button>
  </div>

  <div class="section-label">Tasks</div>
  <div class="task-list" id="task-list">
    <div style="color:#d1d5db;font-size:14px;padding:32px;text-align:center;border:1.5px dashed #e5e7eb;border-radius:10px">No tasks yet.</div>
  </div>
  <div class="sprint-submit-row" id="sprint-submit-row">
    <button class="btn-primary" onclick="confirmLock()">Lock Sprint</button>
  </div>
</div>

<!-- ===== SETTINGS VIEW ===== -->
<div class="view" id="view-settings">
  <div class="page-heading">
    <div class="page-heading-text"><h1>Settings</h1><p>Manage sprint capacity, categories, and sprint state.</p></div>
  </div>
  <div class="card">

    <div class="settings-block">
      <div class="settings-block-title">Sprint Capacity</div>
      <div class="settings-block-desc">Set the total effort hours available for this sprint.</div>
      <div class="settings-row">
        <input type="number" id="cap-input" value="70" min="1" step="1" style="width:110px" />
        <span style="font-size:14px;color:#6b7280;font-weight:500">hours</span>
        <button class="btn-primary" onclick="updateCap()">Save</button>
      </div>
    </div>

    <hr class="divider">

    <div class="settings-block">
      <div class="settings-block-title">Categories</div>
      <div class="settings-block-desc">Add categories that can be selected when creating tasks.</div>
      <div class="cat-list" id="cat-list"></div>
      <div class="cat-add-row">
        <input type="text" id="cat-input" placeholder="e.g. Frontend, Backend, Design..." onkeydown="if(event.key==='Enter')addCategory()" />
        <button class="btn-primary" onclick="addCategory()">Add</button>
      </div>
    </div>

    <hr class="divider">

    <div class="settings-block">
      <div class="settings-block-title">Unlock Sprint</div>
      <div class="settings-block-desc">Sprint is currently <span id="sprint-state-text" style="font-weight:700;color:#16a34a">unlocked</span>. Unlocking allows task selection to be changed.</div>
      <button class="btn-ghost" id="unlock-btn" onclick="unlockSprint()" style="display:none">Unlock Sprint</button>
      <span id="already-unlocked-msg" style="font-size:13px;color:#9ca3af">Sprint is already unlocked.</span>
    </div>

    <hr class="divider">

    <div class="settings-block">
      <div class="settings-block-title">Clear Sprint</div>
      <div class="settings-block-desc">Remove all task selections and reset the sprint. This cannot be undone.</div>
      <button class="btn-danger" onclick="openConfirm('clear')">Clear Sprint</button>
    </div>

  </div>
</div>

<!-- ===== CREATE / EDIT TASK POPUP ===== -->
<div class="backdrop" id="task-popup-backdrop" onclick="closePopupBg(event,'task-popup-backdrop')">
  <div class="popup">
    <button class="popup-close" onclick="closeTaskPopup()">&#215;</button>
    <div class="popup-title" id="task-popup-title">Create Task</div>
    <div class="form-group">
      <label>Task Name</label>
      <input type="text" id="p-name" placeholder="e.g. Redesign checkout flow" />
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea id="p-desc" placeholder="What needs to be done and why?"></textarea>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <div class="form-group">
        <label>Effort (hrs)</label>
        <input type="number" id="p-effort" placeholder="e.g. 8" min="0.5" step="0.5" />
      </div>
      <div class="form-group">
        <label>Category</label>
        <select id="p-cat"><option value="">-- Select --</option></select>
      </div>
    </div>
    <div class="form-group">
      <label>Priority</label>
      <div class="priority-row">
        <div class="priority-btn" data-p="low" onclick="selectPriority('low')">Low</div>
        <div class="priority-btn sel-medium" data-p="medium" onclick="selectPriority('medium')">Medium</div>
        <div class="priority-btn" data-p="high" onclick="selectPriority('high')">High</div>
      </div>
    </div>
    <div class="popup-actions">
      <button class="btn-ghost" onclick="closeTaskPopup()">Cancel</button>
      <button class="btn-primary" id="task-popup-save-btn" onclick="saveTask()">Create Task</button>
    </div>
  </div>
</div>

<!-- ===== DETAIL VIEW POPUP ===== -->
<div class="backdrop" id="detail-backdrop" onclick="closePopupBg(event,'detail-backdrop')">
  <div class="popup" style="max-width:500px">
    <button class="popup-close" onclick="closeDetail()">&#215;</button>
    <div class="popup-title" id="det-name"></div>
    <div class="modal-sec-label">Description</div>
    <div class="modal-desc-text" id="det-desc"></div>
    <div class="modal-meta">
      <div class="meta-box"><div class="meta-key">Effort</div><div class="meta-val" id="det-effort"></div></div>
      <div class="meta-box"><div class="meta-key">Priority</div><div class="meta-val" id="det-priority"></div></div>
      <div class="meta-box"><div class="meta-key">Category</div><div class="meta-val" id="det-tag"></div></div>
      <div class="meta-box"><div class="meta-key">Status</div><div class="meta-val" id="det-status"></div></div>
    </div>
    <div class="popup-actions" id="det-actions"></div>
  </div>
</div>

<!-- ===== CONFIRM POPUP ===== -->
<div class="backdrop" id="confirm-backdrop" onclick="closePopupBg(event,'confirm-backdrop')">
  <div class="popup confirm-popup">
    <button class="popup-close" onclick="closeConfirmPopup()">&#215;</button>
    <div class="popup-title" id="confirm-title">Are you sure?</div>
    <div class="confirm-msg" id="confirm-msg"></div>
    <div class="popup-actions">
      <button class="btn-ghost" onclick="closeConfirmPopup()">Cancel</button>
      <button class="btn-danger" id="confirm-action-btn">Confirm</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
  /* ---- STATE ---- */
  let tasks = [], capacity = 70, sprintLocked = false;
  let selectedPriority = 'medium', editingId = null, deletingId = null;
  let activeFilter = 'all', modalTaskId = null;
  let categories = [];

  /* ---- API ---- */
  async function api(action, data) {
    const opts = data !== undefined
      ? { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) }
      : { method: 'GET' };
    const res = await fetch(`api.php?action=${action}`, opts);
    if (!res.ok) { toast('Server error.'); throw new Error(await res.text()); }
    return res.json();
  }

  /* ---- NORMALISE DB ROW -> JS OBJECT ---- */
  function normTask(t) {
    return {
      id:       parseInt(t.id),
      name:     t.name,
      desc:     t.description || 'No description provided.',
      effort:   parseFloat(t.effort),
      tag:      t.category || 'General',
      priority: t.priority || 'medium',
      accepted: parseInt(t.accepted) === 1,
    };
  }

  /* ---- INIT ---- */
  async function init() {
    const data = await api('init');
    tasks        = data.tasks.map(normTask);
    categories   = data.categories;
    capacity     = data.capacity;
    sprintLocked = !!data.locked;
    document.getElementById('cap-input').value = capacity;
    renderTable();
    renderSettings();
    renderCatList();
  }

  /* ---- VIEWS ---- */
  function showView(v) {
    document.querySelectorAll('.view').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.nav-tab').forEach((t, i) => t.classList.toggle('active',
      (i===0&&v==='tasks') || (i===1&&v==='dashboard') || (i===2&&v==='settings')));
    document.getElementById('view-' + v).classList.add('active');
    if (v === 'dashboard') { renderDashboard(); updateMeter(); }
    if (v === 'settings')  { document.getElementById('cap-input').value = capacity; renderSettings(); renderCatList(); }
    if (v === 'tasks')     { renderTable(); }
  }

  /* ---- PRIORITY ---- */
  function selectPriority(p) {
    selectedPriority = p;
    document.querySelectorAll('#task-popup-backdrop .priority-btn').forEach(b => {
      b.className = 'priority-btn';
      if (b.dataset.p === p) b.classList.add('sel-' + p);
    });
  }

  /* ---- TASK POPUP ---- */
  function openCreatePopup() {
    editingId = null;
    selectedPriority = 'medium';
    document.getElementById('task-popup-title').textContent    = 'Create Task';
    document.getElementById('task-popup-save-btn').textContent = 'Create Task';
    document.getElementById('p-name').value   = '';
    document.getElementById('p-desc').value   = '';
    document.getElementById('p-effort').value = '';
    selectPriority('medium');
    populateCatSelect();
    document.getElementById('p-cat').value = '';
    document.getElementById('task-popup-backdrop').classList.add('open');
  }

  function openEditPopup(id) {
    const t = tasks.find(x => x.id === id);
    if (!t) return;
    editingId = id;
    selectedPriority = t.priority;
    document.getElementById('task-popup-title').textContent    = 'Edit Task';
    document.getElementById('task-popup-save-btn').textContent = 'Save Changes';
    document.getElementById('p-name').value   = t.name;
    document.getElementById('p-desc').value   = t.desc === 'No description provided.' ? '' : t.desc;
    document.getElementById('p-effort').value = t.effort;
    selectPriority(t.priority);
    populateCatSelect();
    document.getElementById('p-cat').value = t.tag;
    document.getElementById('task-popup-backdrop').classList.add('open');
  }

  function populateCatSelect() {
    const sel = document.getElementById('p-cat');
    const cur = sel.value;
    sel.innerHTML = '<option value="">-- Select --</option>' +
      categories.map(c => `<option value="${esc(c)}">${esc(c)}</option>`).join('');
    sel.value = cur;
  }

  function closeTaskPopup() {
    document.getElementById('task-popup-backdrop').classList.remove('open');
    editingId = null;
  }

  async function saveTask() {
    const name   = document.getElementById('p-name').value.trim();
    const desc   = document.getElementById('p-desc').value.trim();
    const effort = parseFloat(document.getElementById('p-effort').value);
    const tag    = document.getElementById('p-cat').value;
    if (!name)           { toast('Task name is required.'); return; }
    if (!effort || effort <= 0) { toast('Enter a valid effort estimate.'); return; }

    const payload = { name, desc: desc || '', effort, tag: tag || 'General', priority: selectedPriority };

    if (editingId !== null) {
      payload.id = editingId;
      const res = await api('update_task', payload);
      if (res.success) {
        const idx = tasks.findIndex(x => x.id === editingId);
        if (idx !== -1) tasks[idx] = normTask(res.task);
        toast('Task updated.');
      }
    } else {
      const res = await api('create_task', payload);
      if (res.success) {
        tasks.push(normTask(res.task));
        toast('Task created.');
      }
    }
    closeTaskPopup();
    renderTable();
  }

  /* ---- TABLE ---- */
  function renderTable() {
    const tbody = document.getElementById('tasks-tbody');
    if (!tasks.length) {
      tbody.innerHTML = '<tr class="empty-row"><td colspan="7">No tasks yet. Click "Create Task" to add one.</td></tr>';
      return;
    }
    const pc = { low: 'badge-low', medium: 'badge-medium', high: 'badge-high' };
    tbody.innerHTML = tasks.map(t => `
      <tr>
        <td class="td-name">${esc(t.name)}</td>
        <td class="td-desc">${esc(t.desc)}</td>
        <td><span class="cat-chip">${esc(t.tag)}</span></td>
        <td><span class="badge ${pc[t.priority]}">${cap(t.priority)}</span></td>
        <td>${t.effort} hrs</td>
        <td><span class="badge ${t.accepted ? 'badge-accepted' : 'badge-pending'}">${t.accepted ? 'Accepted' : 'Pending'}</span></td>
        <td>
          <div class="td-actions">
            <button class="tbl-btn" onclick="openDetailFromTable(${t.id})">View</button>
            <button class="tbl-btn" onclick="openEditPopup(${t.id})">Edit</button>
            <button class="tbl-btn del" onclick="openConfirm('delete',${t.id})">Delete</button>
          </div>
        </td>
      </tr>`).join('');
  }

  /* ---- DETAIL POPUP ---- */
  function openDetailFromTable(id) { openDetail(id, false); }
  function openDetailFromDash(e, id) { e.stopPropagation(); openDetail(id, true); }

  function openDetail(id, fromDash) {
    modalTaskId = id;
    refreshDetail(id, fromDash);
    document.getElementById('detail-backdrop').classList.add('open');
  }

  function refreshDetail(id, fromDash) {
    const t = tasks.find(x => x.id === id);
    if (!t) return;
    const pc = { low: '#3b82f6', medium: '#d97706', high: '#ef4444' };
    document.getElementById('det-name').textContent    = t.name;
    document.getElementById('det-desc').textContent    = t.desc;
    document.getElementById('det-effort').textContent  = t.effort + ' hrs';
    document.getElementById('det-priority').innerHTML  = `<span style="color:${pc[t.priority]}">${cap(t.priority)}</span>`;
    document.getElementById('det-tag').textContent     = t.tag;
    document.getElementById('det-status').innerHTML    = t.accepted
      ? '<span style="color:#16a34a">Accepted</span>'
      : '<span style="color:#d97706">Pending</span>';
    const actions = document.getElementById('det-actions');
    if (fromDash && !sprintLocked) {
      actions.innerHTML = t.accepted
        ? `<button class="btn-primary" style="background:#ef4444" onclick="modalToggle()">Remove from Sprint</button><button class="btn-ghost" onclick="closeDetail()">Close</button>`
        : `<button class="btn-primary" onclick="modalToggle()">Accept Task</button><button class="btn-ghost" onclick="closeDetail()">Close</button>`;
    } else {
      actions.innerHTML = `<button class="btn-ghost" onclick="closeDetail()">Close</button>`;
    }
  }

  async function modalToggle() {
    if (modalTaskId === null) return;
    const res = await api('toggle_accept', { id: modalTaskId });
    if (!res.success) { toast(res.error || 'Not enough capacity.'); return; }
    const t = tasks.find(x => x.id === modalTaskId);
    if (t) t.accepted = res.accepted;
    renderDashboard(); renderTable(); updateMeter();
    refreshDetail(modalTaskId, true);
  }

  function closeDetail() { document.getElementById('detail-backdrop').classList.remove('open'); modalTaskId = null; }

  /* ---- CONFIRM POPUP ---- */
  function openConfirm(type, id) {
    if (type === 'delete') {
      deletingId = id;
      document.getElementById('confirm-title').textContent = 'Delete Task?';
      document.getElementById('confirm-msg').textContent   = 'This task will be permanently removed and cannot be undone.';
      document.getElementById('confirm-action-btn').onclick = () => deleteTask();
    } else if (type === 'lock') {
      document.getElementById('confirm-title').textContent = 'Lock Sprint?';
      document.getElementById('confirm-msg').textContent   = 'The selected tasks will be locked into the sprint. No changes can be made until you unlock from Settings.';
      document.getElementById('confirm-action-btn').onclick = () => lockSprint();
    } else if (type === 'clear') {
      document.getElementById('confirm-title').textContent = 'Clear Sprint?';
      document.getElementById('confirm-msg').textContent   = 'All task selections will be reset and the sprint will be unlocked. This cannot be undone.';
      document.getElementById('confirm-action-btn').onclick = () => clearSprint();
    }
    document.getElementById('confirm-backdrop').classList.add('open');
  }

  function closeConfirmPopup() { document.getElementById('confirm-backdrop').classList.remove('open'); deletingId = null; }

  async function deleteTask() {
    if (deletingId === null) return;
    const res = await api('delete_task', { id: deletingId });
    if (res.success) {
      tasks = tasks.filter(x => x.id !== deletingId);
      closeConfirmPopup();
      renderTable(); renderDashboard(); updateMeter();
      toast('Task deleted.');
    }
  }

  /* ---- DASHBOARD ---- */
  function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.toggle('active', c.dataset.f === f));
    renderDashboard();
  }

  function renderDashboard() {
    const list = document.getElementById('task-list');
    document.getElementById('lock-banner').style.display      = sprintLocked ? 'flex' : 'none';
    document.getElementById('sprint-submit-row').style.display = sprintLocked ? 'none' : 'flex';
    const filtered = activeFilter === 'all' ? tasks : tasks.filter(t => t.priority === activeFilter);
    if (!filtered.length) {
      list.innerHTML = '<div style="color:#d1d5db;font-size:14px;padding:32px;text-align:center;border:1.5px dashed #e5e7eb;border-radius:10px">No tasks yet.</div>';
      return;
    }
    const usedSoFar = tasks.filter(t => t.accepted).reduce((s, t) => s + t.effort, 0);
    list.innerHTML = filtered.map(t => {
      const exceed = !t.accepted && (usedSoFar + t.effort) > capacity;
      return `
      <div class="task-card ${t.accepted ? 'selected' : ''} ${exceed ? 'over-budget' : ''} ${sprintLocked ? 'locked-card' : ''}"
           onclick="${!sprintLocked && !exceed ? `toggleAccept(${t.id})` : 'void 0'}">
        ${exceed ? '<span class="over-label">Over Budget</span>' : ''}
        <div class="tc-check">
          <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l3 3 5-6" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="tc-dot ${t.priority}"></div>
        <div class="tc-body">
          <div class="tc-name">${esc(t.name)}</div>
          <span class="tc-tag">${esc(t.tag)}</span>
        </div>
        <div class="tc-hrs">${t.effort} hrs</div>
        <button class="tc-info" onclick="openDetailFromDash(event,${t.id})">i</button>
      </div>`;
    }).join('');
  }

  async function toggleAccept(id) {
    if (sprintLocked) return;
    const res = await api('toggle_accept', { id });
    if (!res.success) { toast(res.error || 'Not enough capacity.'); return; }
    const t = tasks.find(x => x.id === id);
    if (t) t.accepted = res.accepted;
    renderDashboard(); renderTable(); updateMeter();
  }

  function updateMeter() {
    const used      = tasks.filter(t => t.accepted).reduce((s, t) => s + t.effort, 0);
    const remaining = Math.max(0, capacity - used);
    const pct       = Math.min(100, (used / capacity) * 100);
    const fmt       = n => n % 1 === 0 ? n : n.toFixed(1);
    document.getElementById('d-remaining').innerHTML = `${fmt(remaining)} <span>/ ${capacity} hrs</span>`;
    document.getElementById('d-used').textContent    = `${fmt(used)} hrs used`;
    document.getElementById('d-pct').textContent     = `${Math.round(pct)}% of capacity`;
    const bar = document.getElementById('d-bar');
    bar.style.width = pct + '%';
    bar.className   = 'bar-fill' + (pct >= 100 ? ' over' : pct >= 75 ? ' warn' : '');
  }

  function confirmLock() {
    if (!tasks.filter(t => t.accepted).length) { toast('Select at least one task before locking.'); return; }
    openConfirm('lock');
  }

  async function lockSprint() {
    await api('lock_sprint');
    sprintLocked = true;
    closeConfirmPopup(); renderDashboard(); renderSettings();
    toast('Sprint locked.');
  }

  async function unlockSprint() {
    await api('unlock_sprint');
    sprintLocked = false;
    renderDashboard(); renderSettings();
    toast('Sprint unlocked.');
  }

  async function clearSprint() {
    await api('clear_sprint');
    tasks.forEach(t => t.accepted = false);
    sprintLocked = false;
    closeConfirmPopup(); renderDashboard(); renderTable(); updateMeter(); renderSettings();
    toast('Sprint cleared.');
  }

  /* ---- SETTINGS ---- */
  async function updateCap() {
    const v = parseFloat(document.getElementById('cap-input').value);
    if (!v || v <= 0) { toast('Enter a valid capacity.'); return; }
    await api('update_capacity', { capacity: v });
    capacity = v;
    updateMeter();
    toast('Capacity saved.');
  }

  function renderSettings() {
    const stateText  = document.getElementById('sprint-state-text');
    const unlockBtn  = document.getElementById('unlock-btn');
    const alreadyMsg = document.getElementById('already-unlocked-msg');
    if (sprintLocked) {
      stateText.textContent = 'locked'; stateText.style.color = '#ef4444';
      unlockBtn.style.display = 'inline-flex'; alreadyMsg.style.display = 'none';
    } else {
      stateText.textContent = 'unlocked'; stateText.style.color = '#16a34a';
      unlockBtn.style.display = 'none'; alreadyMsg.style.display = 'block';
    }
  }

  /* ---- CATEGORIES ---- */
  function renderCatList() {
    const el = document.getElementById('cat-list');
    if (!categories.length) { el.innerHTML = '<span style="font-size:13px;color:#d1d5db">No categories yet.</span>'; return; }
    el.innerHTML = categories.map((c, i) => `
      <div class="cat-item">
        ${esc(c)}
        <button class="cat-remove" onclick="removeCategory(${i})" title="Remove">&#215;</button>
      </div>`).join('');
  }

  async function addCategory() {
    const input = document.getElementById('cat-input');
    const val   = input.value.trim();
    if (!val) return;
    if (categories.map(c => c.toLowerCase()).includes(val.toLowerCase())) { toast('Category already exists.'); return; }
    const res = await api('add_category', { name: val });
    if (res.success) { categories = res.categories; input.value = ''; renderCatList(); toast('Category added.'); }
  }

  async function removeCategory(i) {
    const name = categories[i];
    const res  = await api('remove_category', { name });
    if (res.success) { categories = res.categories; renderCatList(); }
  }

  /* ---- HELPERS ---- */
  function closePopupBg(e, id) {
    if (e.target === document.getElementById(id)) {
      if (id === 'task-popup-backdrop') closeTaskPopup();
      else if (id === 'detail-backdrop') closeDetail();
      else if (id === 'confirm-backdrop') closeConfirmPopup();
    }
  }
  function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
  function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
  function toast(msg) { const t = document.getElementById('toast'); t.textContent = msg; t.classList.add('show'); setTimeout(() => t.classList.remove('show'), 2400); }

  init();
</script>
</body>
</html>
