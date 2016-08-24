<script>
    var c = document.getElementById("getpartent");
    var f = c.parentNode;
    var h = window.location.pathname.substring(1);
    
    if(f && f.id.length<=0){
        var url = window.location.protocol+ "//" + window.location.host + window.location.port + "/admin" + "#" + h;
        //alert(url);
        window.location.href = url;
    }
    
    //pageSetup();
</script>