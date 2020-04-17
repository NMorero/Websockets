@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <input type="text" name="admin" id="admin" value="{{Auth::id()}}" hidden>
    <chats :user="{{ auth()->user() }}"></chats>

</div>

@endsection
