function documentList(user_token) {
	$("#document_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#documents_container").show();
	$.get("/src/documents.php?user_token="+user_token,function(result) {
		var documents = jQuery.parseJSON(result);
		if(documents.active == true) {
			$("#document_list").html("");
			if(typeof documents.list != 'undefined') {
				jQuery.each( documents.list, function( i, val ) {
					if(i > 1) { //Skip "." and ".."
						$("#document_list").append('<a href="'+val.url+'" class="list-group-item" target="_blank"><i class="fa fa-file-'+val.ext+'-o"></i>&nbsp;&nbsp;<span class="doc_name">'+val.name+'</span></a>');
					}
				});
				$("#search-docs").fadeIn();
			} else {
				$("#document_list").html("No Documents Available");
			}
		} else {
			$("#document_list").html("No Documents Available");
		}
	});
}

$("#search-docs").keyup(function () {
	var searchTerm = $("#search-docs").val();
	
	if(searchTerm == "" || searchTerm == undefined) {
		$("#document_list a").removeClass('out').addClass('in').show();
	} else {
		var listItem = $('#document_list').children('a');
		
		var searchSplit = searchTerm;
		
		//extends :contains to be case insensitive
		$.extend($.expr[':'], {
			'containsi': function(elem, i, match, array)
			{
			return (elem.textContent || elem.innerText || '').toLowerCase()
			.indexOf((match[3] || "").toLowerCase()) >= 0;
			}
		});
			
		$("#document_list a span.doc_name").not(":containsi('" + searchSplit + "')").each(function(e)   {
			$(this).closest("a").addClass('out').removeClass('in').fadeOut();
		});
		
		$("#document_list a span.doc_name:containsi('" + searchSplit + "')").each(function(e) {
			$(this).closest("a").removeClass('out').addClass('in').fadeIn('slow');
		});
		
	  	var calCount = $('#document_list .in').length;
	    //$('.list-count').text(jobCount + ' items');
	    
	    //shows empty state text when no jobs found
	    if(calCount == '0') {
	      $('#no_doc_results').fadeIn();
	    }
	    else {
	      $('#no_doc_results').hide();
	    }
	}
});