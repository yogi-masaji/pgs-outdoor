<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Parking Designer</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            overflow-x: hidden;
        }

        /* Sidebar Base */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #0f172a;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            left: 0;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .sidebar {
            width: 80px;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 80px;
        }

        body.sidebar-collapsed .sidebar .nav-link span,
        body.sidebar-collapsed .sidebar .logo-text,
        body.sidebar-collapsed .sidebar .user-details {
            display: none;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            border-radius: 12px;
            margin: 4px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.2s;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2563eb;
            color: white;
        }

        .navbar-custom {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Designer Specific Styles */
        #parking-canvas-container {
            background: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            height: calc(100vh - 180px);
            cursor: grab;
        }

        #parking-canvas-container:active {
            cursor: grabbing;
        }

        .slot-rect {
            transition: fill 0.2s, stroke 0.2s;
            cursor: pointer;
        }

        .rotate-handle {
            cursor: alias;
            fill: #fb923c;
        }

        .rotate-line {
            stroke: #fb923c;
            stroke-width: 2;
        }

        .json-output {
            background: #0f172a;
            color: #34d399;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            padding: 15px;
            border-radius: 12px;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #334155;
        }

        .designer-card {
            border: none;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .bg-image-layer {
            pointer-events: none;
            opacity: 0.8;
        }

        .input-group-sm .form-control {
            font-size: 11px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar d-flex flex-column" id="sidebar">
        <div class="p-4 d-flex align-items-center gap-2">
            <div class="bg-primary p-2 rounded-3 text-white flex-shrink-0">
                <i data-lucide="layout-dashboard"></i>
            </div>
            <h4 class="mb-0 fw-bold logo-text">GLPI Admin</h4>
        </div>

        <nav class="nav flex-column mt-3">
            <a href="#" class="nav-link active">
                <i data-lucide="map-pin" size="20"></i>
                <span>Designer Parkir</span>
            </a>
            <a href="#" class="nav-link">
                <i data-lucide="ticket" size="20"></i>
                <span>Tiket</span>
            </a>
        </nav>

        <div class="mt-auto p-4 border-top border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-3 bg-dark p-2 rounded-3">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                    style="width: 40px; height: 40px;">AS</div>
                <div class="small user-details overflow-hidden">
                    <div class="fw-bold text-truncate text-white">Abdullah Satrio</div>
                    <div style="font-size: 10px; color: #94a3b8;">Super Admin</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <header class="navbar-custom p-3">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light border rounded-3 p-2" onclick="toggleSidebar()">
                        <i data-lucide="menu" size="20"></i>
                    </button>
                    <h5 class="mb-0 fw-bold text-dark">Layout Floor 1</h5>
                </div>

                <div class="d-flex gap-2">
                    <div class="btn-group p-1 bg-light rounded-3 border">
                        <button class="btn btn-sm px-3 fw-bold" id="btnPreview"
                            onclick="setMode('preview')">Preview</button>
                        <button class="btn btn-sm px-3 fw-bold btn-primary rounded-2 shadow-sm" id="btnDesign"
                            onclick="setMode('design')">Design</button>
                    </div>
                    <button class="btn btn-success fw-bold px-4 rounded-3 shadow-sm" onclick="addNewSlot()">
                        <i data-lucide="plus" size="18" class="me-1"></i> Tambah Slot
                    </button>
                </div>
            </div>
        </header>

        <div class="p-4">
            <div class="row g-4">
                <!-- Designer Area -->
                <div class="col-lg-8">
                    <div class="card designer-card">
                        <div class="card-body p-2">
                            <div id="parking-canvas-container">
                                <svg id="parking-svg" width="100%" height="100%" viewBox="0 0 1000 600"
                                    preserveAspectRatio="xMidYMid slice">
                                    <defs>
                                        <pattern id="grid" width="40" height="40"
                                            patternUnits="userSpaceOnUse">
                                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#334155"
                                                stroke-width="0.5" />
                                        </pattern>
                                    </defs>

                                    <rect width="20000" height="20000" x="-10000" y="-10000" fill="#0f172a" />
                                    <rect width="20000" height="20000" x="-10000" y="-10000" fill="url(#grid)"
                                        opacity="0.5" />

                                    <g id="viewport-group">
                                        <image id="floor-base" href={{ asset('images/Floor2.svg') }} x="0" y="0"
                                            width="1000" height="600" class="bg-image-layer" />
                                        <g id="slots-container"></g>
                                    </g>
                                </svg>

                                <div class="position-absolute bottom-0 end-0 p-3">
                                    <div class="badge bg-dark bg-opacity-75 p-2 px-3 border border-secondary border-opacity-25"
                                        id="zoomLevel">
                                        Zoom: 100%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Area -->
                <div class="col-lg-4">
                    <div class="card designer-card mb-4" id="slotDetailCard" style="display: none;">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Detail Slot: <span id="lblSlotId" class="text-primary"></span>
                                </h6>
                                <button class="btn btn-outline-danger btn-sm border-0" onclick="deleteSelectedSlot()">
                                    <i data-lucide="trash-2" size="16"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <!-- Posisi -->
                            <div class="row g-2 text-center mb-3">
                                <div class="col-4">
                                    <div class="p-2 bg-light rounded-3">
                                        <small class="text-muted d-block" style="font-size: 9px;">POSISI X</small>
                                        <span class="fw-bold" id="valX">0</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-light rounded-3">
                                        <small class="text-muted d-block" style="font-size: 9px;">POSISI Y</small>
                                        <span class="fw-bold" id="valY">0</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-light rounded-3">
                                        <small class="text-muted d-block" style="font-size: 9px;">ROTASI</small>
                                        <span class="fw-bold" id="valRot">0°</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ukuran Kontrol -->
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="text-muted mb-1" style="font-size: 10px; font-weight: 700;">LEBAR
                                        (W)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" id="inputWidth" class="form-control rounded-3"
                                            oninput="updateSlotSize('width', this.value)">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted mb-1" style="font-size: 10px; font-weight: 700;">TINGGI
                                        (H)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" id="inputHeight" class="form-control rounded-3"
                                            oninput="updateSlotSize('height', this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card designer-card">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h6 class="mb-0 fw-bold text-muted uppercase"
                                style="font-size: 10px; letter-spacing: 1px;">DATA KOORDINAT (JSON)</h6>
                        </div>
                        <div class="card-body pt-0">
                            <pre class="json-output mb-3" id="jsonDisplay">{}</pre>
                            <button class="btn btn-dark w-100 rounded-3" onclick="copyJson()">
                                <i data-lucide="copy" size="16" class="me-2"></i> Salin Data
                            </button>
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-3">
                                <p class="small text-blue-700 mb-0" style="font-size: 11px;">
                                    <i data-lucide="info" size="12" class="me-1"></i>
                                    Ubah angka Lebar/Tinggi di atas untuk mengecilkan slot yang dipilih.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        lucide.createIcons();

        // --- STATE ---
        let slots = {
            'A-01': {
                x: 200,
                y: 150,
                width: 40,
                height: 80,
                rotate: 0,
                status: 'available'
            },
            'A-02': {
                x: 260,
                y: 150,
                width: 40,
                height: 80,
                rotate: 0,
                status: 'occupied'
            },
            'B-01': {
                x: 500,
                y: 200,
                width: 40,
                height: 80,
                rotate: 45,
                status: 'available'
            },
        };
        let view = {
            x: 0,
            y: 0,
            scale: 1
        };
        let editMode = true;
        let selectedId = null;
        let dragging = null;

        const svg = document.getElementById('parking-svg');
        const slotsContainer = document.getElementById('slots-container');

        // --- CORE FUNCTIONS ---

        function render() {
            slotsContainer.innerHTML = '';

            svg.setAttribute('viewBox', `${view.x} ${view.y} ${1000 * view.scale} ${600 * view.scale}`);
            document.getElementById('zoomLevel').innerText = `Zoom: ${Math.round(1/view.scale * 100)}%`;

            Object.entries(slots).forEach(([id, slot]) => {
                const isSelected = selectedId === id;
                const g = document.createElementNS("http://www.w3.org/2000/svg", "g");
                g.setAttribute('transform', `translate(${slot.x}, ${slot.y}) rotate(${slot.rotate})`);

                const hit = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                hit.setAttribute('x', -slot.width / 2 - 15);
                hit.setAttribute('y', -slot.height / 2 - 15);
                hit.setAttribute('width', slot.width + 30);
                hit.setAttribute('height', slot.height + 30);
                hit.setAttribute('fill', 'transparent');
                hit.style.cursor = editMode ? 'move' : 'pointer';

                hit.onmousedown = (e) => {
                    e.preventDefault();
                    startDrag(e, id, 'move');
                };
                g.appendChild(hit);

                const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                rect.setAttribute('x', -slot.width / 2);
                rect.setAttribute('y', -slot.height / 2);
                rect.setAttribute('width', slot.width);
                rect.setAttribute('height', slot.height);
                rect.setAttribute('rx', 4);
                rect.classList.add('slot-rect');

                let fillColor = slot.status === 'occupied' ? '#ef4444' : '#10b981';
                if (isSelected) fillColor = '#3b82f6';

                rect.setAttribute('fill', fillColor);
                rect.setAttribute('fill-opacity', isSelected ? '1' : '0.8');
                rect.setAttribute('stroke', isSelected ? '#ffffff' : 'rgba(255,255,255,0.4)');
                rect.setAttribute('stroke-width', isSelected ? 3 : 1);
                g.appendChild(rect);

                const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text.setAttribute('text-anchor', 'middle');
                text.setAttribute('dy', '.3em');
                text.setAttribute('fill', 'white');
                text.style.fontSize = '9px';
                text.style.fontWeight = '700';
                text.style.pointerEvents = 'none';
                text.textContent = id;
                g.appendChild(text);

                if (editMode && isSelected) {
                    const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                    line.setAttribute('x1', 0);
                    line.setAttribute('y1', -slot.height / 2);
                    line.setAttribute('x2', 0);
                    line.setAttribute('y2', -slot.height / 2 - 25);
                    line.classList.add('rotate-line');
                    g.appendChild(line);

                    const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                    handle.setAttribute('cx', 0);
                    handle.setAttribute('cy', -slot.height / 2 - 25);
                    handle.setAttribute('r', 8);
                    handle.classList.add('rotate-handle');
                    handle.onmousedown = (e) => {
                        e.preventDefault();
                        startDrag(e, id, 'rotate');
                    };
                    g.appendChild(handle);
                }

                slotsContainer.appendChild(g);
            });

            updateInfoPanel();
            document.getElementById('jsonDisplay').innerText = JSON.stringify(slots, null, 2);
        }

        // --- INTERACTION LOGIC ---

        function getMouseCoords(e) {
            const pt = svg.createSVGPoint();
            pt.x = e.clientX;
            pt.y = e.clientY;
            const transformed = pt.matrixTransform(svg.getScreenCTM().inverse());
            return {
                x: transformed.x,
                y: transformed.y
            };
        }

        function startDrag(e, id, type) {
            e.stopPropagation();
            const coords = getMouseCoords(e);
            selectedId = id;

            dragging = {
                type,
                id,
                startX: coords.x,
                startY: coords.y,
                initX: id ? slots[id].x : 0,
                initY: id ? slots[id].y : 0,
                initViewX: view.x,
                initViewY: view.y
            };
            render();
        }

        svg.onmousedown = (e) => {
            if (e.target === svg || e.target.id === 'floor-base' || e.target.getAttribute('fill') === 'url(#grid)') {
                startDrag(e, null, 'pan');
            }
        };

        window.onmousemove = (e) => {
            if (!dragging) return;
            const coords = getMouseCoords(e);
            const dx = coords.x - dragging.startX;
            const dy = coords.y - dragging.startY;

            if (dragging.type === 'move') {
                slots[dragging.id].x = dragging.initX + dx;
                slots[dragging.id].y = dragging.initY + dy;
            } else if (dragging.type === 'rotate') {
                const s = slots[dragging.id];
                const angle = Math.atan2(coords.y - s.y, coords.x - s.x) * (180 / Math.PI);
                s.rotate = angle + 90;
            } else if (dragging.type === 'pan') {
                view.x = dragging.initViewX - dx;
                view.y = dragging.initViewY - dy;
            }
            render();
        };

        window.onmouseup = () => {
            dragging = null;
        };

        svg.onwheel = (e) => {
            e.preventDefault();
            const factor = e.deltaY > 0 ? 1.05 : 0.95;
            view.scale = Math.min(Math.max(view.scale * factor, 0.1), 10);
            render();
        };

        // --- UI HELPERS ---

        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
        }

        function setMode(mode) {
            editMode = (mode === 'design');
            document.getElementById('btnDesign').className = editMode ?
                'btn btn-sm px-3 fw-bold btn-primary rounded-2 shadow-sm' : 'btn btn-sm px-3 fw-bold';
            document.getElementById('btnPreview').className = !editMode ?
                'btn btn-sm px-3 fw-bold btn-primary rounded-2 shadow-sm' : 'btn btn-sm px-3 fw-bold';
            selectedId = null;
            render();
        }

        function addNewSlot() {
            const id = `SLOT-${Object.keys(slots).length + 1}`;
            slots[id] = {
                x: view.x + (500 * view.scale),
                y: view.y + (300 * view.scale),
                width: 30,
                height: 60,
                rotate: 0,
                status: 'available'
            };
            selectedId = id;
            render();
        }

        function updateSlotSize(attr, value) {
            if (selectedId && slots[selectedId]) {
                const num = parseInt(value);
                if (!isNaN(num) && num > 0) {
                    slots[selectedId][attr] = num;
                    render();
                }
            }
        }

        function deleteSelectedSlot() {
            if (selectedId) {
                delete slots[selectedId];
                selectedId = null;
                render();
            }
        }

        function updateInfoPanel() {
            const card = document.getElementById('slotDetailCard');
            if (selectedId && slots[selectedId]) {
                card.style.display = 'block';
                document.getElementById('lblSlotId').innerText = selectedId;
                document.getElementById('valX').innerText = Math.round(slots[selectedId].x);
                document.getElementById('valY').innerText = Math.round(slots[selectedId].y);
                document.getElementById('valRot').innerText = Math.round(slots[selectedId].rotate) + '°';

                // Update input values
                document.getElementById('inputWidth').value = slots[selectedId].width;
                document.getElementById('inputHeight').value = slots[selectedId].height;
            } else {
                card.style.display = 'none';
            }
        }

        function copyJson() {
            const text = JSON.stringify(slots, null, 2);
            navigator.clipboard.writeText(text).then(() => {
                alert("Data JSON berhasil disalin!");
            });
        }

        window.onload = render;
    </script>
</body>

</html>
