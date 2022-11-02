<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset("css/order.css") }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <nav class="navbar mb-2">
    <a class="navbar-brand" href="#">
      <img src="{{asset("static_images/logo.png")}}"  class="logo-brand">
      Sokha Store
    </a>

  </nav>
  <div class="container-fluid " id="pro-order">
    <ul class="scrol-nav col-12">
        <li>
            <a class="nav-item nav-link cat-item cat-active category-filter" href="#" data-id="0">ទាំងអស់</a>
        </li>
        {{-- @foreach ($category as $item)
        <li>
            <a class="nav-item nav-link cat-item category-filter " href="#" data-id="{{ $item->id }}">{{ $item->title }}</a>
        </li>
        @endforeach --}}


    </ul>
    <div class="col-12 product-list ">
      <div class="row product-wrap">
        {{-- @foreach ($products as $item )
        <div class="grid-product">
          {{-- <img src="{{ $item->image ? asset("uploads/product/".$item->image ) : asset("image/no_pro.png")}}" > --}}

          {{-- <div style="height: 50px; text-overflow: ellipsis;" >{{ $item->name }}</div>
          <div class="btn-order">
            <span class="value-order" style="top:-5px;right:-5px" data-id="{{ $item->id }}" data-toggle="{{ $item->name }}" data-show="{{ $item->unit_price }}">0</span>
            <button class="btn-descrease bg-warning" style="outline: none; color:azure"><i class="fa fa-minus"></i></button>
            <button class="btn-increase bg-info" style="outline: none;  color:azure" ><i class="fa fa-plus"></i></button>
          </div>
        </div>
        @endforeach --}} --}}

      </div>
    </div>
    <div class="footer-submit">
      មើលការកុម៉ង់
    </div>

  </div>

  <div class="invoice-page">
    <div class="invoice-header">
        <div class="btn-back"><i class="fa fa-angle-left fa-lg"></i></div>
        <h2>វិកយប័ត្រ</h2>

    </div>
    <div class="cus-profile">
      <label>បញ្ចូលលេខទូរស័ព្ទ</label>
        <div class="pro-more" style="display: grid">
          
          <input type="number" name="phone" id="phone" style="  
            border: 1px solid green;
            
            "  
          >
          <span style="font-size: 14px; color:red; display:none" id="warning-phone">សូមបំពេញលេខទូរស័ព្ទដែលត្រឹមត្រូវ</span>
        </div>
        
    </div>
   
    <div class="invoice-body">
        <div class="list-order">
            <table class="table table-borderless">
                <tbody id="list-data">

                </tbody>
              </table>
              <div style="float: right;margin-right:20px; border-top: 1px solid;padding-top: 10px;" id="total">

              </div>
        </div>
    </div>
    <div class="btn-action">
        <button  type="button" onclick="{window.location.reload()}">បោះបង់</button>
        <button type="button" class="" id="pro-ordering">ទិញ</button>
    </div>
  </div>

</body>

<script src="{{ asset('js/bot.js') }}"></script>
<script >
    data_store_array ={};
    final_array =[]
    final_data =[];
    response_message =[];
  $(document).ready(function(){


    $('body').on('click', '.btn-descrease', function(){
        var pro_id = $(this).siblings(".value-order").attr("data-id")
      var value = $(this).siblings(".value-order").text()
      if (value >0){
        value --;

      }
      $(this).siblings(".value-order").html(value)
      if (value== 0){
        delete data_store_array[pro_id]

      }

      if (Object.keys(data_store_array).length <= 0){
        $(".footer-submit").css({display:"none"})
      }
     
    })

    $('body').on('click', '.btn-increase', function(){
        var pro_id = $(this).siblings(".value-order").attr("data-id")
      var value = $(this).siblings(".value-order").text()
      if (value <100){
        value ++;

      }
      if (value > 0){
        $(".footer-submit").css({display:"block"})
      }

      $(this).siblings(".value-order").html(value)

      data_store_array[pro_id] = value
    });


    // invoice page

    $(".footer-submit").on('click', function(){
        $("#pro-order").css({"display":"none", "transition":'1s'})
        $(".invoice-page").css({"display":"block" , "transition":'1s'});
        sub_key = Object.keys(data_store_array)
        sub_val = Object.values(data_store_array)
        final_array =[]
        data_id = [];
        for(var i=0; i<sub_key.length; i++){
            data_id.push(parseInt(sub_key[i]))
          final_array.push(
            {
              "pro_id" :sub_key[i],
              "value" : data_store_array[sub_key[i]]
            }
          )
        }

        pro_name =[];
        pro_price=[];

        $.ajax({
        url:"{{ route('order.price_byid') }}",
        data:{
            id:data_id
        },
        success:function(res){
            res.data.map((obj)=>{
                pro_name.push(obj.name);
                pro_price.push(obj.unit_price);
            })
            showDataList(final_array, pro_name, pro_price)

            }
        })
        // getPriceDataById(data_id)
    })
    $(".btn-back").on('click', function(){
        $("#pro-order").css({"display":"block", "transition":'1s'})
        $(".invoice-page").css({"display":"none"})
    });

    // show product by category ==================================================

    $(".category-filter").on('click', function(){
        $(".category-filter").css({"backgroundColor":"bisque"})
        $(this).css({"backgroundColor":"cyan"})
        var cat_id = $(this).attr("data-id")
        $.ajax({
          url: "{{ route('order.filter_cat') }}",
          data:{
            cate:cat_id
          },

          success:function(res){
            var list_html=""
            res.map((obj)=>{
                if (obj.image['img_name'] == "")
                {

                    console.log("No Product")
                    url = "{{ asset('image/no_pro.png') }}";
                }
                else {
                    url = "{{ asset('uploads/product') }}"+"/"+obj.image;
                }
                if (obj.id == data_store_array[obj.id]){
                }
                sub_data = Object.keys(data_store_array)
                pre_value =0
                for (var i=0; i<sub_data.length; i++){
                    if (sub_data[i] == obj.id){
                        pre_value=data_store_array[obj.id]
                    }
                }


                list_html +='<div class="grid-product">'+
                            `<img src="${url}" >`+
                            `<marquee scrollamount="1">${obj.name}</marquee>`+
                            '<div class="btn-order">'+
                                '<span class="value-order" data-id="'+obj.id+'">'+pre_value+'</span>'+
                                '<button class="btn-descrease bg-warning" style="outline: none; color:azure"><i class="fa fa-minus"></i></button>'+
                                '<button class="btn-increase bg-info" style="outline: none;  color:azure" ><i class="fa fa-plus"></i></button>'+
                            '</div>'+
                            '</div>'
            })
            $(".product-wrap").html(list_html)
          }
        });
    })
  });

  function showDataList(list, list1,list2){
    list_data="";
    total =0;
    list.forEach((element, index )=> {

        final_data.push({
            "pro_id":element.pro_id,
            "qty":element.value,
            "sub_total":(list2[index]*element.value).toFixed(2)

        });
      total +=list2[index]*element.value;

      list_data +=
        ' <tr >'+
            '<th scope="row">'+(index+1)+'</th>'+
            '<td class="col-6">'+list1[index]+'</td>'+
            '<td class="col-2">'+element.value+'</td>'+
            '<td class="col-2">'+list2[index]+'$</td>'+
            '<td class="col-2">'+(list2[index]*element.value).toFixed(2)+'$</td>'+
          '</tr>'

      response_message.push({
        name : list1[index],
        qty : element.value,
        price : list2[index],
        sub_total :(list2[index]*element.value).toFixed(2)
      });

    });
    $("#list-data").html(list_data)
    $("#total").html(
        '<h5>'+total.toFixed(2)+'$</h5>'
    )
  }



$("#pro-ordering").on('click', function(){

    numberphone = $("#phone").val();
    var chat_id =0;
    $.ajax({
        url:"{{ route('order.customer') }}",
        type:"get",
        data:{
            phone :numberphone
        },
        success:function(res){
            chat_id = res.success.chatid
            if (res.success === false){
                $("#warning-phone").css({display:"block"})
                // if(confirm("លេខរបស់លោកអ្នកមិនត្រឹមត្រូវទេ សូមធ្វើការចុះឈ្មោះងាយៗ តាមលេតេក្រាម ដោយចុចពាក្យថាខាងក្រោម")){
                //     window.open("https://t.me/sokha_stores_bot/bot?start=sokhastock");
                // }
            }else{
                    $.ajax({
                    url:"{{ route('order.product') }}",
                    type:"POST",
                    data:{
                        data:final_data,
                        custo:res.success.id,
                        _token: '{{csrf_token()}}'
                    },
                    success:function(data){
                        status_  = senMessageBot(chat_id, response_message);
                        final_array=[];
                        final_data =[];
                        setTimeout(() => {
                              
                            }, 10000);
                      
                    }
                })
            }
        }
    })

})


</script>

</html>
