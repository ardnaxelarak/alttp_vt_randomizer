@extends('layouts.default', ['title' => sprintf('%s - ', $hash)])

@section('content')
    <Multiloader hash="{{ $hash }}"></Multiloader>
@overwrite
