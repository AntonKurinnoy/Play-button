@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="form-group">
                <label>Бонусные баллы на счету: </label> {!! $bonusPoints !!}<br>
                <label>Выигранные товары: </label> {!! $stuff !!}
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-success btn-lg" name="play">ИГРАТЬ!</button>
            </div>

            <div class="form-group result tpl">
                <label>Вы выиграли: </label> <label name="prize"></label>

                <div name="money" class="tpl">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="money" id="money" value="" checked>
                        <label class="form-check-label" for="money">

                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="money" id="bonusPoints" value="">
                        <label class="form-check-label" for="bonusPoints">

                        </label>
                    </div>
                </div>

                <div name="bonusPoints" class="tpl">
                    <label name="bonusPoints"></label>
                </div>
                <div name="stuff" class="tpl">
                    333
                </div>

                <div class="form-group">
                    <label>Получить приз?</label><br>
                    <button type="button" class="btn btn-primary" name="confirm" onclick="confirm()">Да</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.reload()">Нет</button>
                </div>

            </div>

        </div>


    </div>
</div>

@push('scripts')
    <script src="{{asset('js/scripts.js')}}?<?php echo filemtime("js/scripts.js")?>"></script>
@endpush

@endsection
