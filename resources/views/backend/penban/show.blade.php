@extends('layouts.backend')

@section('css')
        <link rel="stylesheet" href="{{asset('assets/backend/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
@endsection

@section('js')
        <script src="{{asset('assets/backend/assets/vendor/datatables.net/js/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/backend/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
        <script src="{{asset('assets/backend/assets/js/components/datatables-init.js')}}"></script>
@endsection
@section('content')
    
<section class="page-content container-fluid">
    <div class="row">
        <div class="col">
            <div class="tab-content">
                <div class="tab-pane fadeIn active" id="tab-1">
                    <div class="card">
                        <h5 class="card-header">Show Data Penerima Bantuan </h5>
                         <i class ="zmdi zmdi-badge-check-zmdi-hc-fw"></i>
                         <!-- {{ $mobil->merk->merk_mobil }} {{ $mobil->jenis->jenis_mobil }} -->
                        </h5>
                        <div class ="card-body">
                            <img src="{{ asset('assets/img/mobil/' .$penban->foto_diri.'') }}"
                            style="width:300px;" class="float-left rounded m-r-30 m-b-30">
                            <br>
                            <p>
                                NIK : 
                                <button class="btn btn-danger btn-floating">
                                    {{ $penban->nik }}
                                </button>
                            </p>
                            <p> No KK :
                                <button class="btn btn-accent btn-floating">
                                    {{ $penban->no_kk}}
                                </button>
                            </p>
                            <p> Nama :
                                <button class="btn btn-accent btn-floating">
                                    {{ $penban->nama}}
                                </button>
                            </p>
                            <p> Status Pekerjaan :
                                <button class="btn btn-accent btn-floating">
                                    {{ $penban->status_pkj}}
                                </button>
                            </p>
                            <p> Jenis Kelamin :
                                <button class="btn btn-accent btn-floating">
                                    {{ $penban->jk}}
                                </button>
                            </p>
                            <p> Jenis Bantuan :
                                <button class="btn btn-accent btn-floating">
                                    {{ $penban->jb}}
                                </button>
                            </p>
                            <p>
                               Tanggal : {{ $penban->created_at->format('d M Y H:i') }}WIB
                            </p>
                        </div>	
                    </div>
                </div>       
            </div>
        </div>
    </div>
</section>
@endsection