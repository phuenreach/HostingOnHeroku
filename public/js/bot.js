
var TOKEN = "5464961569:AAHQcEY1VUzaxUT2fYNc0b68swxC4ykKkJA";

function senMessageBot(chat_id, data){
   message = '<b>វិកយបត្រ</b>\n\nឈ្មោះហាង\t\t\សុខាបោះដុំ\n\n រាយមុខទំនិញ\n\n'+convertImage(data);
   $.ajax({
        type:"POST",
        url:`https://api.telegram.org/bot${TOKEN}/sendMessage`,
        data: {
            chat_id:chat_id,
            text: message,
            parse_mode: 'html',
        },
        success: function (res) {
            if(res.ok === true){
              window.location.reload(true);
            }
        },
        error: function (error) {
            console.error(error);
            alert("error failed");
        }
   });
}

function convertImage(data){
    var listdata ="";

    data.forEach(element => {
        listdata +=element.name+'\t\t'+element.qty+'\t\t'+element.price+'$\t\t'+element.sub_total+'$\n\n'
    });

    return listdata;


}




function sendAlertMessage(chat_id){
      const  message = 'ប្រសិនបើទំនិញ ទៅបានដាក់នៅហាងរបស់អ្នករួចរាល់ហើយ នឹងត្រឹមត្រូវសូមចុចប៊ូតុងខាងក្រោម';
      const inLineKeyboard ={
        "inline_keyboard": [
          [
            {
              "text": "ចុចទីនេះ",
              "callback_data": "confirm"
            },
          ]
        ]
      };

      $.ajax({
        type:"POST",
        url:`https://api.telegram.org/bot${TOKEN}/sendMessage`,
        data: {
            chat_id:chat_id,
            text: message,
            reply_markup:inLineKeyboard
        },
        success: function (res) {
            console.debug(res);
        },
        error: function (error) {
            console.error(error);
            alert("error failed");
        }
    });

    }



function b64toBlob(dataURI) {
      var byteString = atob(dataURI.split(',')[1]);
      var ab = new ArrayBuffer(byteString.length);
      var ia = new Uint8Array(ab);
  
      for (var i = 0; i < byteString.length; i++) {
          ia[i] = byteString.charCodeAt(i);
      }
      return new Blob([ab], { type: 'image/jpeg' });
  }
  
function sendPhotoInvoice(chat_id, photo){
  var form_da = new FormData();
  var blob = b64toBlob(photo)
  form_da.append("chat_id", chat_id);
  form_da.append("photo", blob);
  form_da.append("caption", "ទំនិញនឹងទៅដល់ហាងរបស់អ្នកទៅតាមទីតាំងដែលអ្នកបានផ្ដល់\n សូមធ្វើកាពិនិត្យទំនិញតាមរយះវិក្កយបត្រខាងលើ");

  $.ajax({
    method:"POST",
    async:true,
    url:`https://api.telegram.org/bot${TOKEN}/sendPhoto`,
    data:form_da,
    processData: false,
    contentType: false,
    success:function(){
      window.location.reload(true);
    }
  })
}

