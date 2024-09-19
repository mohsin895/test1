@php
    $data = [
        [
            "title" => "Bronchoscopy",
            "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, deserunt commodi nostrum consequuntur atque, vero ipsa ea accusamus eligendi aliquam quae deleniti cum explicabo aliquid animi dolor! Doloribus, ullam commodi?",
        ],
        [
            "title" => "Parathyroid Scan",
            "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, deserunt commodi nostrum consequuntur atque, vero ipsa ea accusamus eligendi aliquam quae deleniti cum explicabo aliquid animi dolor! Doloribus, ullam commodi?",
        ],
        [
            "title" => "Orthotics",
            "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, deserunt commodi nostrum consequuntur atque, vero ipsa ea accusamus eligendi aliquam quae deleniti cum explicabo aliquid animi dolor! Doloribus, ullam commodi?",
        ],
        [
            "title" => "Parathyroid Scan",
            "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, deserunt commodi nostrum consequuntur atque, vero ipsa ea accusamus eligendi aliquam quae deleniti cum explicabo aliquid animi dolor! Doloribus, ullam commodi?",
        ],
        [
            "title" => "Orthotics",
            "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, deserunt commodi nostrum consequuntur atque, vero ipsa ea accusamus eligendi aliquam quae deleniti cum explicabo aliquid animi dolor! Doloribus, ullam commodi?",
        ],
    ]
@endphp


@extends('frontend.master')

@section('banner')
<div class="bg-[url('frontend/home-page-img/about-2.webp')] object-cover h-[400px] flex items-center justify-center">
    <h2 class="text-center text-white text-5xl title-font">Faq</h2>
</div>
@endsection

@section('content')
    <div>
        <div class="container mx-auto py-20">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    @foreach($data as $item)
                    <div class=" rounded">

                        <button class="open w-full mb-2 hover:text-black/50 duration-300 text-md font-medium py-5 px-4 text-black rounded bg-black/5 flex items-center justify-between">
                            {{ $item['title'] }}
                            <i class="text-md hover:text-black/50 font-bold ph ph-plus"></i>
                        </button>

                        <p class="text-black/60 p-4  border border-black/5">
                            {{ $item['description'] }}
                        </p>
                    </div>
                    @endforeach
                </div>
                <div>
                    <img 
                        src="frontend/home-page-img/faq-1.webp" 
                        alt=""
                        class="rounded w-full"
                    >
                    <div class="py-4">
                        <h3 class="text-black mb-2 text-lg font-semibold">Intensive Caring</h3>
                        <p class="text-black/50 ">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, deserunt commodi nostrum consequuntur atque, vero ipsa ea accusamus eligendi aliquam quae deleniti cum explicabo aliquid animi dolor! Doloribus, ullam commodi?
                        </p>
                    </div>
                    <div class="py-4">
                        <h3 class="text-black mb-2 text-lg font-semibold">Best of Care</h3>
                        <p class="text-black/50 ">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Optio alias fugit sunt odio nesciunt qui culpa, dicta, odit tenetur ullam ipsa suscipit ab. Natus consequatur eius, temporibus adipisci nobis animi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    let open = document.getElementsByClassName('open');
    console.log('open');
</script>