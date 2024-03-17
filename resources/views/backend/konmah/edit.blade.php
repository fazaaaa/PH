@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/backend/assets/vendor/select2/select2.min.css')}}">
@endsection

@section('js')
    <script src="{{asset('assets/backend/assets/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('assets/backend/assets/js/components/select2-init.js')}}"></script>
    <script>
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    </script>
@endsection
@section('content')
<section class="page-content container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <center>
                    <div class="card-header">Edit Data Kondisi Rumah Penerima Bantuan</div>
                    </center>
                        <div class="card-body">
                            <form action="{{route('konru.update',$konru->id)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input class="form-control{{ $errors->has('nik') ? ' has-error' : '' }}" type="text"
                                    name="nik" id="" required>
                                   @if ($errors->has('nik'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nik') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Id Penerima</label>
                                    <select class="form-control{{ $errors->has('penban') ? ' has-error' : '' }}" type="text"
                                    name="id_penerima" id="s2_demo3" required>
                                    <option disabled selected>--Pilih--</option>
                                        @foreach ($penban as $data)
                                            <option value="{{$data->id}}">
                                            {{$data->id_penerima}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('penban'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('penban') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Tempat Berteduh</label>
                                    <input class="form-control{{ $errors->has('tmpt_berteduh') ? ' has-error' : '' }}" type="text"
                                    name="tmpt_berteduh" id="" required>
                                   @if ($errors->has('tmpt_berteduh'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('tmpt_berteduh') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Lantai</label>
                                    <input class="form-control{{ $errors->has('jenis_lantai') ? ' has-error' : '' }}" type="text"
                                    name="jenis_lantai" id="" required>
                                   @if ($errors->has('jenis_lantai'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jenis_lantai') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Dinding</label>
                                    <input class="form-control  {{ $errors->has('jenis_dinding') ? ' has-error' : '' }}" type="text"
                                    name="jenis_dinding" id="" required>
                                   @if ($errors->has('jenis_dinding'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jenis_dinding') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Fasilitas MCK</label>
                                    <input class="form-control  {{ $errors->has('fasilitas_mck') ? ' has-error' : '' }}" type="text"
                                    name="fasilitas_mck" id="" required>
                                   @if ($errors->has('fasilitas_mck'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fasilitas_mck') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Sumber Listrik</label>
                                    <input class="form-control {{ $errors->has('sumber_listrik') ? ' has-error' : '' }}" type="text"
                                    name="sumber_listrik" id="" required>
                                   @if ($errors->has('sumber_listrik'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sumber_listrik') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Foto Rumah</label>
                                    <input class="form-control{{ $errors->has('foto_rumah') ? ' has-error' : '' }}" type="file"
                                    name="foto_rumah" id="" required>
                                   @if ($errors->has('foto_rumah'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('foto_rumah') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-info btn-rounded btn-block">
                                        Simpan Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection