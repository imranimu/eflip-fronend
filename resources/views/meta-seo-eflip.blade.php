    
     @if(url()->full() == 'https://www.eflip.com')
     
        <title>Trending News Today: Best Current News Sites, Most Popular News Eflip</title>
		<meta name="description" content="Discover top trending news sites at Eflip! Explore the most popular latest news websites and fascinating sites filled with the latest information and trending news. Click here to view all 503 sites, and visit each site by clicking on the corresponding images">
		<meta name="keywords" content="trending news today, top trending news online, best latest news website, most interesting websites, current screens of websites, most popular news eflip">
    
    @elseif(url()->full() == 'https://www.eflip.com/browse/News/random11')

		<title>eflip</title>
		<meta name="description" content="eflip">
		<meta name="keywords" content="eflip">

	@else
	
		@php $totalHourlydailyWebsites=App\Models\Website::where('status',0)->whereIn('get_image_hourly',['H','D'])->count(); @endphp
        <title>Latest Information & News About All {{  $totalHourlydailyWebsites }} Websites At Eflip</title>
		
	@endif
	
	
<meta name="classification" content="trending 503 news toda, best current news sites online, top trending news online"/>
<meta name="search engines" content="Aeiwi, Alexa, AllTheWeb, AltaVista, AOL Netfind, Anzwers, Canada, DirectHit, EuroSeek, Excite, Overture, Go, Google, HotBot. InfoMak, Kanoodle, Lycos, MasterSite, National Directory, Northern Light, SearchIt, SimpleSearch, WebsMostLinked, WebTop, What-U-Seek, AOL, Yahoo, WebCrawler, Infoseek, Excite, Magellan, LookSmart, CNET, Googlebot"/>
<meta name="robots" content="index,follow"/>
<link rel="pingback" href="https://www.eflip.com/sitemap.xml"/>

<meta name="author" content=" Eflip "/>

<meta name="google-site-verification" content="EeFN5GQ-H02_aFynpgt26_0KXCMTzKElUJ0WdSecalI" />

<meta name="Language" content="English" />
<meta name="YahooSeeker" content="index,follow">
<meta name="msnbot" content="index,follow">
<meta name="googlebot" content="index,follow"/>
<meta name="allow-search" content="yes">

<meta name="robots" content="noodp, noydir"/>

<meta name="rating" content="General">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YCSDYSQGZL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-YCSDYSQGZL');
</script>

    
    
    
    
    
    
    
    
    