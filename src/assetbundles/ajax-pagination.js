(function(){

    // ajax request
    var dynamicPaginationCurrentRequest = null;  
    function dynamicPaginationChangePage(pageNumber, updatedUrl = null){

        var wrapperElement = document.querySelector(dynamicPaginationSettings.wrapperSelector);

        // add loading class
        if(dynamicPaginationSettings.usePreloader){
            wrapperElement.classList.add(dynamicPaginationSettings.cssClassLoading);
        }        

        // cancel earlier request if exists
        if(dynamicPaginationCurrentRequest != null) {
            dynamicPaginationCurrentRequest.abort();
        }        

        // scroll
        if(dynamicPaginationSettings.scrollOnRequest){            
            wrapperElement.scrollIntoView({behavior: "smooth"});
        }

        // url
        requestUrl = dynamicPaginationSettings.endpointUrl + '/' + dynamicPaginationSettings.pageTrigger + pageNumber;
        params = new URLSearchParams(dynamicPaginationSettings.requestData).toString()
        requestUrl = requestUrl + '&' + params

        // before request event
        const eventBefore = new Event('dynamic-pagination-before');
        document.querySelector(dynamicPaginationSettings.wrapperSelector).dispatchEvent(eventBefore);

        // create request
        dynamicPaginationCurrentRequest = new XMLHttpRequest();
        dynamicPaginationCurrentRequest.open('GET', requestUrl, true);

        // on complete
        dynamicPaginationCurrentRequest.onreadystatechange = function () {
            if (dynamicPaginationCurrentRequest.readyState === 4) {
                if(dynamicPaginationSettings.usePreloader){
                    wrapperElement.classList.remove(dynamicPaginationSettings.cssClassLoading);
                }                
                if (dynamicPaginationCurrentRequest.status === 200) {

                    // update content
                    document.querySelector(dynamicPaginationSettings.wrapperSelector).outerHTML = dynamicPaginationCurrentRequest.response

                    // update url
                    if(updatedUrl){
                        history.pushState(pageNumber, null, updatedUrl);
                    } 

                    // after request event
                    const eventAfter = new Event('dynamic-pagination-after');
                    document.querySelector(dynamicPaginationSettings.wrapperSelector).dispatchEvent(eventAfter);

                } else {
                    // error event
                    const eventError = new Event('dynamic-pagination-error');
                    document.querySelector(dynamicPaginationSettings.wrapperSelector).dispatchEvent(eventError);                
                }
            }
        };


        // send
        dynamicPaginationCurrentRequest.send(null);
    }

    // back/forward button
    window.addEventListener('popstate', function(e) {
        if(e.state){
            dynamicPaginationChangePage(e.state);
        }else{
            dynamicPaginationChangePage(dynamicPaginationSettings.initialPage);
        }
    });

    // pagination click
    document.querySelector('body').addEventListener('click', e => {
        e.preventDefault();
        const { target } = e;
        if (
            target.matches('[data-' + dynamicPaginationSettings.linkNumberDataAttribute + ']') && 
            !target.matches('[data-' + dynamicPaginationSettings.linkDisabledAttribute + ']')
        ) {
            var updatedUrl = target.getAttribute('href');
            var selectedPage = target.getAttribute('data-' + dynamicPaginationSettings.linkNumberDataAttribute);
            dynamicPaginationChangePage(selectedPage, updatedUrl);    
        }
    });

})();
