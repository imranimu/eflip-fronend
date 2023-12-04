system = require('system');
var page = require('webpage').create();
page.settings.clearMemoryCaches = true;
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36';
 //give the page upto a maximum of a minute to load
  //then break and throw error message
page.settings.resourceTimeout = 900000; //millisecond // 15min
//page.settings.resourceTimeout = 3000000; //millisecond // 50min
var store = system.args[1];
var dest = system.args[2];
var image_name = system.args[3];

var url = 'http://'+store;


page.viewportSize = {
    width: 1024,
    height: 768
};

block_urls = ['gstatic.com', 'adocean.pl', 'gemius.pl', 'twitter.com', 'facebook.net', 'facebook.com', 'planplus.rs'];
page.onResourceRequested = function(requestData, request){
    for(url in block_urls) {
        if(requestData.url.indexOf(block_urls[url]) !== -1) {
            request.abort();
            console.log(requestData.url + " aborted");
            return;
        }
    }
}


page.open(url, function(status) 
{
  console.log(status);  
  if (status == 'success') {
    page.evaluate(function() {
     
      if (getComputedStyle(document.body, null).backgroundColor === 'rgba(0, 0, 0, 0)') {
        document.body.bgColor = 'white';
      }

    });

    window.setTimeout(function () 
    {
      page.clipRect = {
        top: 0,
        left: 0,
        width: 1024,
        height: 768
      };
      page.render(dest+'/'+image_name,{format: 'jpeg', quality: '100'});
      page.stop();
      page.release();
      page.close();
      phantom.exit();
      
    }, 2000);
  } else {
    page.stop();
    page.release();
    page.close();
    phantom.exit(1);
  }
  

});

//page.onResourceError = function(resourceError) {
//  
//    page.stop();
//    page.release();
//    page.close();
//    phantom.exit(1);
//};

page.onResourceTimeout = function(request) {
    page.stop();
    page.release();
    page.close();
    phantom.exit(1);
    
};