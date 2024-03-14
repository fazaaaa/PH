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
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Data Kondisi Rumah Penerima Bantuan</h5><br>
                    <center>
                            <a href="{{ route('mobil.create') }}"
                                class="la la-cloud-upload btn btn-info btn-rounded btn-floating btn-outline">&nbsp;Tambah Data
                            </a>
                    </center>
                    <div class="card-body">
                        <table id="bs4-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>ID Penerima</th>
                                    <th>Tempat Berteduh</th>
                                    <th>Jenis Lantai</th>
                                    <th>Jenis Dinding</th>
                                    <th>Sumber Listrik</th>
                                    <th>Foto Rumah</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($konru as $data)
                                <tr>
                                    <td>{{$data->nik}}</td>
                                    <td>{{$data->id_penerima}}</td>
                                    <td>{{$data->tmpt_berteduh}}</td>
                                    <td>{{$data->jenis_lantai}}</td>
                                    <td>{{$data->jenis_dinding}}</td>
                                    <td>{{$data->sumber_listrik}}</td>
                                    <td><img class="img-thumbnail" src="{{asset('assets/img/mobil/' .$data->foto_rumah. '')}}"
                                        style="width:100px; height:70px;" alt="Foto Rumah"></td>
                                
                                    <td style="text-align: center;">
                                        <form action="{{route('konru.destroy', $data->id)}}" method="post">
                                            {{csrf_field()}}
                                        <a href="{{route('konru.edit', $data->id)}}"
                                             class="zmdi zmdi-edit btn btn-warning btn-rounded btn-floating btn-outline">
                                        </a>
                                        <a href="{{route('konru.show', $data->id)}}"
                                            class="zmdi zmdi-eye btn btn-success btn-rounded btn-floating btn-outline">
                                        </a>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="zmdi zmdi-delete btn-rounded btn-floating btn btn-dangerbtn btn-danger btn-outline"></button>
                                        </form>
                                     </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection