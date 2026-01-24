@extends('layouts.dashboard')
@section('content')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #print-area,
                #print-area * {
                    visibility: visible;
                }

                #print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                #btn-print,
                .btn-secondary {
                    display: none !important;
                }
    }
        </style>

            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Payrolls</h3>
                            <p class="text-subtitle text-muted">Handle Payroll data</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Payroll</li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                Create
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="print-area">

                            <div class="container mt-4">

                                <div class="card shadow-sm p-4">

                                    <h3 class="mb-4 text-center">Payroll Slip</h3>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="mb-3">
                                                <label class="form-label fw-bold"><strong>Employee</strong></label> 
                                                <p class="border rounded p-2 bg-light">{{ $payroll->employee->fullname }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold"><strong>Salary</strong></label>
                                                <p class="border rounded p-2 bg-light">{{ number_format($payroll->salary) }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold"><strong>Deductions</strong></label>
                                                <p class="border rounded p-2 bg-light">{{ number_format($payroll->deductions) }}</p>
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="mb-3">
                                                <label class="form-label fw-bold"><strong>Bonuses</strong></label>
                                                <p class="border rounded p-2 bg-light">{{ number_format($payroll->bonuses) }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold"><strong>Net Salary</strong></label>
                                                <p class="border rounded p-2 bg-light text-success fw-bold">
                                                    {{ number_format($payroll->net_salary) }}
                                                </p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold"><strong>Pay Date</strong></label>
                                                <p class="border rounded p-2 bg-light">{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d F Y') }}</p>
                                            </div>

                                        </div>
                                        </div>

                                    </div>

                                    <div class="mt-4 d-flex justify-content-between">
                                        <button onclick="window.print()" id="btn-print" class="btn btn-primary"><span class="bi bi-printer"></span> Print</button>
                                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to List</a>
                                    </div>

                                </div>

                                </div>


                </section>
            </div>

            <script>
                document.getElementById('btn-print').addEventListener('click', function () {
                    window.print();
                });
            </script>
@endsection