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
                        <h5 class="card-header">Show Data Kondisi Rumah Penerima Bantuan </h5>
                         <i class ="zmdi zmdi-badge-check-zmdi-hc-fw"></i>
                         {{ $konru->id_penerima->id_penerima }} 
                        </h5>
                        <div class ="card-body">
                            <img src="{{ asset('assets/img/mobil/' .$konru->foto_rumah.'') }}"
                            style="width:300px;" class="float-left rounded m-r-30 m-b-30">
                            <br>
                            <p>
                                NIK : 
                                <button class="btn btn-danger btn-floating">
                                    {{ $konru->nik }}
                                </button>
                            </p>
                            <p> Tempat Berteduh :
                                <button class="btn btn-accent btn-floating">
                                    {{ $konru->tmpt_berteduh}}
                                </button>
                            </p>
                            <p> Jenis Lantai :
                                <button class="btn btn-accent btn-floating">
                                    {{ $konru->jenis_lantai}}
                                </button>
                            </p>
                            <p> Jenis Dinding :
                                <button class="btn btn-accent btn-floating">
                                    {{ $konru->jenis_dinding}}
                                </button>
                            </p>
                            <p> Fasilitas MCK :
                                <button class="btn btn-accent btn-floating">
                                    {{ $konru->fasilitas_mck}}
                                </button>
                            </p>
                            <p> Sumber Listrik :
                                <button class="btn btn-accent btn-floating">
                                    {{ $konru->sumber_listrik}}
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