@extends('layout.app')

@section('content')
    <div class="container-fluid pb-5">

        <!-- GLOBAL FILTERS -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-auto">
                        <h6 class="mb-0 fw-bold text-muted"><i data-lucide="filter" size="16" class="me-2"></i>Filters
                        </h6>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm border-light bg-light">
                            <option>Last 30 Days</option>
                            <option>Last 7 Days</option>
                            <option>This Quarter</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm border-light bg-light">
                            <option>All Departments</option>
                            <option>IT Infrastructure</option>
                            <option>Software Dev</option>
                            <option>Customer Success</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm border-light bg-light">
                            <option>All Priorities</option>
                            <option>Critical</option>
                            <option>High</option>
                        </select>
                    </div>
                    <div class="col-md-auto ms-auto">
                        <button class="btn btn-primary btn-sm px-3 rounded-pill">Apply Changes</button>
                        <button class="btn btn-link text-muted btn-sm text-decoration-none">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 1: EXECUTIVE KPI SUMMARY -->
        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="card p-3 border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                    <p class="text-muted small mb-1 fw-medium">Total Tickets</p>
                    <h3 class="fw-bold mb-1">1,482</h3>
                    <span class="text-success small fw-bold"><i data-lucide="trending-up" size="14"></i> 12% MoM</span>
                </div>
            </div>
            <div class="col-md">
                <div class="card p-3 border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                    <p class="text-muted small mb-1 fw-medium">Open Tickets</p>
                    <h3 class="fw-bold mb-1">342</h3>
                    <span class="text-warning small fw-bold">Active Load</span>
                </div>
            </div>
            <div class="col-md">
                <div
                    class="card p-3 border-0 shadow-sm h-100 border-start border-danger border-4 cursor-pointer hover-shadow transition">
                    <p class="text-muted small mb-1 fw-medium">Tickets Over SLA</p>
                    <h3 class="fw-bold mb-1 text-danger">18</h3>
                    <span class="text-danger small fw-bold">Requires Attention</span>
                </div>
            </div>
            <div class="col-md">
                <div
                    class="card p-3 border-0 shadow-sm h-100 border-start border-success border-4 cursor-pointer hover-shadow transition">
                    <p class="text-muted small mb-1 fw-medium">SLA Compliance</p>
                    <h3 class="fw-bold mb-1 text-success">96.4%</h3>
                    <span class="text-success small fw-bold"><i data-lucide="check" size="14"></i> Above Target</span>
                </div>
            </div>
            <div class="col-md">
                <div class="card p-3 border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                    <p class="text-muted small mb-1 fw-medium">Avg Resolution</p>
                    <h3 class="fw-bold mb-1">3.8h</h3>
                    <span class="text-success small fw-bold"><i data-lucide="trending-down" size="14"></i> 15m
                        faster</span>
                </div>
            </div>
        </div>

        <!-- SECTION 2: TREND & PERFORMANCE -->
        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">Performance Trend: Created vs Resolved</h6>
                        <div class="small text-muted">MTD Performance</div>
                    </div>
                    <div style="height: 320px;">
                        <canvas id="perfTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-4">SLA Compliance by Department</h6>
                    <div style="height: 320px;">
                        <canvas id="slaDeptChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: SLA RISK MONITORING -->
        <div class="row g-4 mb-4">
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-4">Current SLA Status</h6>
                    <div style="height: 220px;" class="mb-4">
                        <canvas id="riskDonutChart"></canvas>
                    </div>
                    <div class="vstack gap-2">
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted"><i data-lucide="circle" size="10"
                                    class="text-success fill-success me-2"></i>On Track</span>
                            <span class="fw-bold">82%</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted"><i data-lucide="circle" size="10"
                                    class="text-warning fill-warning me-2"></i>At Risk</span>
                            <span class="fw-bold">12%</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted"><i data-lucide="circle" size="10"
                                    class="text-danger fill-danger me-2"></i>Over SLA</span>
                            <span class="fw-bold">6%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between">
                        <h6 class="fw-bold mb-0">Top Aging Tickets (Critical)</h6>
                        <a href="#" class="small text-decoration-none">View All Over-SLA</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 mt-3">
                                <thead class="bg-light">
                                    <tr class="small text-muted">
                                        <th class="ps-4">TICKET ID</th>
                                        <th>DEPARTMENT</th>
                                        <th>PRIORITY</th>
                                        <th>AGE</th>
                                        <th class="pe-4 text-end">SLA STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4 fw-bold">#TK-9021</td>
                                        <td>Infrastructure</td>
                                        <td><span class="badge bg-danger-subtle text-danger">Critical</span></td>
                                        <td>4d 12h</td>
                                        <td class="pe-4 text-end"><span class="text-danger fw-bold">Over SLA (12h)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 fw-bold">#TK-8845</td>
                                        <td>Software Dev</td>
                                        <td><span class="badge bg-danger-subtle text-danger">Critical</span></td>
                                        <td>3d 08h</td>
                                        <td class="pe-4 text-end"><span class="text-warning fw-bold">At Risk (02h)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 fw-bold">#TK-8712</td>
                                        <td>Customer Success</td>
                                        <td><span class="badge bg-warning-subtle text-warning">High</span></td>
                                        <td>2d 15h</td>
                                        <td class="pe-4 text-end"><span class="text-success fw-bold">On Track</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: ISSUE ANALYSIS & ROOT CAUSE -->
        <div class="row g-4 mb-4">
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm p-4 h-100 text-center">
                    <h6 class="fw-bold mb-4 text-start">Repeat Issue Rate</h6>
                    <div class="py-4">
                        <h1 class="display-4 fw-bold text-primary mb-0">8.4%</h1>
                        <p class="text-muted small">Target: < 10%</p>
                                <div class="progress mt-3 mx-auto" style="height: 10px; width: 80%;">
                                    <div class="progress-bar bg-primary" style="width: 84%;"></div>
                                </div>
                    </div>
                    <p class="small text-muted mt-auto mb-0">Issues recurring within 30 days of resolution.</p>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-4">Tickets by Category</h6>
                    <div style="height: 250px;">
                        <canvas id="catPieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-4">Top 5 Root Causes (Pareto)</h6>
                    <div style="height: 250px;">
                        <canvas id="rootCauseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: ACTIONABLE INSIGHTS -->
        <div class="row g-4">
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm bg-primary text-white p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i data-lucide="sparkles" size="20"></i>
                        <h6 class="fw-bold mb-0">Executive Insights</h6>
                    </div>
                    <div class="vstack gap-3">
                        <div class="p-3 bg-white bg-opacity-10 rounded-3">
                            <p class="small mb-0"><strong>Network Issues</strong> are driving a 25% increase in SLA
                                breaches this week. Primarily affecting HO location.</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-10 rounded-3">
                            <p class="small mb-0"><strong>Infrastructure Team</strong> is handling 42% of all over-SLA
                                tickets. Resource bottleneck detected.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-3">Recommended Actions</h6>
                    <ul class="list-group list-group-flush small">
                        <li
                            class="list-group-item px-0 py-3 border-light d-flex align-items-center justify-content-between">
                            <span>Reallocate 2 technicians from App Support to Infrastructure</span>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">Assign</button>
                        </li>
                        <li
                            class="list-group-item px-0 py-3 border-light d-flex align-items-center justify-content-between">
                            <span>Schedule preventative maintenance for Branch Jakarta Routers</span>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">Schedule</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Performance Trend Chart
        new Chart(document.getElementById('perfTrendChart'), {
            type: 'line',
            data: {
                labels: ['W1', 'W2', 'W3', 'W4', 'W5'],
                datasets: [{
                        label: 'Created',
                        data: [120, 150, 180, 140, 210],
                        borderColor: '#0f172a',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Resolved',
                        data: [100, 140, 160, 155, 190],
                        borderColor: '#2563eb',
                        tension: 0.4,
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // SLA by Dept Chart
        new Chart(document.getElementById('slaDeptChart'), {
            type: 'bar',
            data: {
                labels: ['Infra', 'Dev', 'CS', 'HR', 'Admin'],
                datasets: [{
                    data: [92, 98, 95, 100, 97],
                    backgroundColor: '#2563eb',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        max: 100
                    }
                }
            }
        });

        // Risk Donut Chart
        new Chart(document.getElementById('riskDonutChart'), {
            type: 'doughnut',
            data: {
                labels: ['On Track', 'At Risk', 'Over SLA'],
                datasets: [{
                    data: [82, 12, 6],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '80%',
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Category Pie Chart
        new Chart(document.getElementById('catPieChart'), {
            type: 'pie',
            data: {
                labels: ['Hardware', 'Network', 'Software', 'Access'],
                datasets: [{
                    data: [40, 25, 20, 15],
                    backgroundColor: ['#0f172a', '#2563eb', '#64748b', '#cbd5e1']
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Root Cause Pareto
        new Chart(document.getElementById('rootCauseChart'), {
            type: 'bar',
            data: {
                labels: ['Old Hardware', 'Config Error', 'User Error', 'ISP Outage', 'Bugs'],
                datasets: [{
                    data: [50, 30, 15, 10, 5],
                    backgroundColor: '#0f172a',
                    borderRadius: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
