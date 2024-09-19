
@extends('frontend.master')

@section('banner')
    @include('frontend.fragment.banner')
@endsection

@section('content')
    @include('frontend.fragment.home.chamber')
    @include('frontend.fragment.home.category-special')
    @include('frontend.fragment.home.appointment')
    @include('frontend.fragment.home.favourite-doctor')
    @include('frontend.fragment.home.favourite-hospital')
    {{-- @include('frontend/fragment/home/ourPartner') --}}

@endsection

