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

                @media print {
                .row {
                    display: flex;
                    flex-wrap: nowrap;
                }

                .col-md-6 {
                     width: 50% !important;
                }
    }

                .no-print {
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
                                <li class="breadcrumb-item">Payroll</li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Payroll Slip</h5>
                    </div>

                    <div class="card-body">

                        <div id="print-area">

                            <div class="text-center mb-4">
                                <img src="https://placehold.co/150x80?text=HRIS" alt="Company Logo" style="height: 80px;">
                                <h4 class="mt-2 mb-0">PT HRIS Nusantara</h4>
                                <small>Jl. Merdeka No. 123, Jakarta</small>
                                <hr>
                            </div>

                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Employee</th>
                                    <td>{{ $payroll->employee->fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Salary</th>
                                    <td>{{ number_format($payroll->salary) }}</td>
                                </tr>
                                <tr>
                                    <th>Bonuses</th>
                                    <td>{{ number_format($payroll->bonuses) }}</td>
                                </tr>
                                <tr>
                                    <th>Deductions</th>
                                    <td>{{ number_format($payroll->deductions) }}</td>
                                </tr>
                                <tr class="table-success fw-bold">
                                    <th>Net Salary</th>
                                    <td>{{ number_format($payroll->net_salary) }}</td>
                                </tr>
                                <tr>
                                    <th>Pay Date</th>
                                    <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d F Y') }}</td>
                                </tr>
                            </table>

                            <div class="row mt-5">
                                <div class="col-md-6 text-center">
                                    <p>Employee Signature</p>
                                    <div style="height: 80px; border-bottom: 1px solid #000;">
                                        <span style="font-family: 'Brush Script MT', cursive; font-size: 24px;">
                                            {{ $payroll->employee->fullname }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 text-center">
                                    <p>Authorized Signature</p>
                                    <div style="height: 80px; border-bottom: 1px solid #000;">
                                        <span style="font-family: 'Brush Script MT', cursive; font-size: 24px;">
                                            HR Manager
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 d-flex justify-content-between no-print">
                            <button onclick="window.print()" id="btn-print" class="btn btn-primary">
                                <span class="bi bi-printer"></span> Print
                            </button>
                            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>

                    </div>
                </div>
            </section>
        </div>

@endsection