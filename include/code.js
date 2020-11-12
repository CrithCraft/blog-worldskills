function edit_post(obj, id) {
    var button_label = obj.parentNode;
    var post = button_label.parentNode;

    // get previous values
    var name_field = post.getElementsByClassName("dhead")[0].innerHTML;
    var text_field = post.getElementsByClassName("dcontent")[0].getElementsByTagName("p")[0].innerHTML;
    var hashtag_field = post.getElementsByClassName("bubble")[0].getElementsByTagName("span")[0].innerHTML;
    
    post.innerHTML = '<div>&nbsp</div>'
        +'<div class="dochead">'
            +'<input type="text" name="name" value="'+name_field+'" required>'
        +'</div>'
        +'<div class="dcontent">'
        +'<img src="/images/app_icon.png">'
            +'<textarea name="description" required>'+text_field+'</textarea>'
        +'</div>'
        +'<div class="bubble" align="right">'
            +'<input type="text" name="hashtag" value="'+hashtag_field.substring(1)+'" required>'
        +'</div>'
        +'<div class="button-label">'
            +'<input type="text" class="id" name="id" value='+id+'>'
            +'<button class="link" onClick="back_post(this,'+"'"+id+"'"+','+"'"+name_field+"'"+','+"'"+text_field+"'"+','+"'"+hashtag_field+"'"+')">Назад</button>'
            +'<input type="submit" class="link" name="edit-post-button" value="ОТПРАВИТЬ">'
        +'</div>';
}

function back_post(obj, id, name, text, hashtag) {
    var button_label = obj.parentNode;
    var post = button_label.parentNode;
    
    post.innerHTML = '<div>&nbsp</div>'
        +'<div class="dochead">'
            +'<span class="dhead">'+name+'</span>'
        +'</div>'
        +'<div class="dcontent">'
        +'<img src="/images/app_icon.png">'
            +'<p>'+text+'</p>'
        +'</div>'
        +'<div class="bubble" align="right">'
            +'<span>'+hashtag+'</span>'
        +'</div>'
        +'<div class="button-label">'
            +'<input type="text" class="id" name="id" value='+id+'>'
            +'<button type="submit" class="link" onClick="edit_post(this,'+id+')">Редактировать</button>'
            +'<input type="submit" class="link" name="delete-post-button" value="Удалить">'
        +'</div>';
}