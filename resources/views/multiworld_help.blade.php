@extends('layouts.default', ['title' => __('navigation.multiworld_setup') . ' - '])

@section('content')
<h1>{{ __('multiworld_help.header') }}</h1>
<div id="multiworld_help" class="card card-body bg-themed">
    <h2>{!! __('multiworld_help.subheader') !!}</h2>

    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.setup.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.setup.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.generation.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.generation.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.hosting.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.hosting.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.downloading.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.downloading.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.loading.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.loading.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.connecting.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.connecting.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
    <div class="card border-info mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">{{ __('multiworld_help.cards.playing.header') }}</h3>
        </div>
        <div class="card-body">
            @foreach (__('multiworld_help.cards.playing.content') as $block)
                <p>{!! $block !!}</p>
            @endforeach
        </div>
    </div>
</div>
@overwrite
