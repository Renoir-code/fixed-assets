/*
cursor_wait = function()
{
    // switch to cursor wait for the current element over
    var elements = $(':hover');
    if (elements.length)
    {
        // get the last element which is the one on top
        elements.last().addClass('cursor-wait');
    }

    // add class to use it in the mouseover selector (to avoid conflicts)
    $('html, body').addClass('cursor-wait');

    // switch to cursor wait for all elements you'll be over
    $('html').on('mouseover', 'body.cursor-wait *', function(e)
    {
        $(e.target).addClass('cursor-wait');
    });
};

remove_cursor_wait = function()
{
    $('html').off('mouseover', 'body.cursor-wait *'); // remove event handler
    $('.cursor-wait').removeClass('cursor-wait'); // get back to default
};
*/

function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }

function print_content()
{    
    //cursor_wait();    
    var restorepage = document.body.innerHTML;
    
    if(document.getElementById('print_content') !== null)
    {
        var printcontent = document.getElementById('print_content').innerHTML;
    }    
   
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
}

function onPrintCompleteCancel() 
{ 
    document.body.onmousemove = ""; 
    window.history.back();
} 

function print_data()
{	
    //$(document.body).css({ 'cursor': 'wait' });
    
    getDivisionsByLocationId().done(function(data){
            var assets = $.parseJSON(data);
           $('#print_content').html(assets); 
           //$('#print_content').append(assets); 
                    
           print_content();
           $('#print_content').hide('fast');
           
           //$(document.body).css({ 'cursor': 'default' });
           //remove_cursor_wait();
    });
}

function getDivisionsByLocationId(){
	return $.ajax({
		url: 'generate_print_version',
		//data: {location_id: location_id},
		async: false
	});
}






