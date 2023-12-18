(function(){

    // ajax request
    var dynamicPaginationCurrentRequest = null;

    let wrapperElement = document.querySelector('[data-dynamic-pagination-wrapper]');
    let dynamicPaginationSettings = JSON.parse(wrapperElement.getAttribute('data-dynamic-pagination-data'));

    function dynamicPaginationChangePage(pageNumber, updatedUrl = null){

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
        wrapperElement.dispatchEvent(eventBefore);

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
                    wrapperElement.innerHTML = dynamicPaginationCurrentRequest.response

                    // update url
                    if(updatedUrl){
                        history.pushState(pageNumber, null, updatedUrl);
                    } 

                    // after request event
                    const eventAfter = new Event('dynamic-pagination-after');
                    wrapperElement.dispatchEvent(eventAfter);

                } else {
                    // error event
                    const eventError = new Event('dynamic-pagination-error');
                    wrapperElement.dispatchEvent(eventError);
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
        let selectorLinkNumber = '[data-' + dynamicPaginationSettings.linkNumberDataAttribute + ']';
        let selectorLinkDisabled = '[data-' + dynamicPaginationSettings.linkDisabledAttribute + ']';
        console.log(dynamicPaginationSettings)
        if (target.matches(selectorLinkNumber) && !target.matches(selectorLinkDisabled)) {
            var updatedUrl = target.getAttribute('href');
            var selectedPage = target.getAttribute('data-' + dynamicPaginationSettings.linkNumberDataAttribute);
            dynamicPaginationChangePage(selectedPage, updatedUrl);    
        }
    });

})();
