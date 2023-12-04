/*
 SocialShare - jQuery plugin
 */
(function ($) {

    function get_class_list(elem){
        if(elem.classList){
            return elem.classList;
        }else{
            return $(elem).attr('class').match(/\S+/gi);
        }
    }

    $.fn.ShareLink = function(options){
        var defaults = {
            title: '',
            text: '',
            image: '',
            url: window.location.href,
            class_prefix: 's_'
        };

        var options = $.extend({}, defaults, options);

        var class_prefix_length = options.class_prefix.length;

        var templates = {
            twitter: 'https://twitter.com/intent/tweet?url={url}&text={text}',
            pinterest: 'https://www.pinterest.com/pin/create/button/?media={image}&url={url}&description={text}',
            facebook: 'https://www.facebook.com/sharer.php?s=100&p[title]={title}&p[summary]={text}&p[url]={url}&p[images][0]={image}',
            vk: 'https://vkontakte.ru/share.php?url={url}&title={title}&description={text}&image={image}&noparse=true',
            linkedin: 'https://www.linkedin.com/shareArticle?mini=true&url={url}&title={title}&summary={text}&source={url}',
            myworld: 'https://connect.mail.ru/share?url={url}&title={title}&description={text}&imageurl={image}',
            odnoklassniki: 'http://odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={url}&st.comments={text}',
            tumblr: 'https://tumblr.com/share?s=&v=3&t={title}&u={url}',
            blogger: 'https://blogger.com/blog-this.g?t={text}&n={title}&u={url}',
            delicious: 'https://delicious.com/save?url={url}&title={title}',
            plus: 'https://plus.google.com/share?url={url}',
            digg: 'https://digg.com/submit?url={url}&title={title}',
            reddit: 'https://reddit.com/submit?url={url}&title={title}',
            stumbleupon: 'https://www.stumbleupon.com/submit?url={url}&title={title}',
            pocket: 'https://getpocket.com/edit?url={url}&title={title}',
            chiq: 'https://www.chiq.com/create/bookmarklet?u={url}&i={image}&d={title}&c={url}',
            qrifier: 'https://qrifier.com/q?inc=qr&type=url&size=350&string={url}',
            qrsrc: 'https://www.qrsrc.com/default.aspx?shareurl={url}',
            qzone: 'https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={url}',
            tulinq: 'https://www.tulinq.com/enviar?url={url}&title={title}',
            misterwong: 'https://www.mister-wong.com/index.php?action=addurl&bm_url={url}&bm_description={title}&bm_notice=',
            sto_zakladok: 'https://www.100zakladok.ru/save/?bmurl={url}&bmtitle={title}',
            two_linkme: 'https://www.2linkme.com/?collegamento={url}&id=2lbar',
            adifni: 'https://www.adifni.com/account/bookmark/?bookmark_url={url}',
            amazonwishlist: 'https://www.amazon.com/gp/wishlist/static-add?u={url}&t={title}',
            amenme: 'https://www.amenme.com/AmenMe/Amens/AmenToThis.aspx?url={url}&title={title}',
            aim: 'https://lifestream.aol.com/share/?url={url}&title={title}&description={text} ',
            aolmail: 'https://webmail.aol.com/25045/aol/en-us/Mail/compose-message.aspx?to=&subject={title}&body={{content}}',
            arto: 'https://www.arto.com/section/linkshare/?lu={url}&ln={title}',
            baidu: 'https://cang.baidu.com/do/add?it={title}&iu={url}&fr=ien&dc={text}',
            bitly: 'httpss://bitly.com/a/bitmarklet?u={url}',
            bizsugar: 'https://www.bizsugar.com/bizsugarthis.php?url={url}',
            blinklist: 'https://www.blinklist.com/blink?u={url}&t={title}&d={text}',
            blip: 'https://blip.pl/dashboard?body={title}%3A%20{url}',
            blogmarks: 'https://blogmarks.net/my/new.php?mini=1&simple=1&url={url}&title={title}&content={text}',
            blurpalicious: 'https://www.blurpalicious.com/submit/?url={url}&title={title}&desc={text}',
            bobrdobr: 'https://bobrdobr.ru/addext.html?url={url}&title={title}&desc={text}',
            bonzobox: 'https://bonzobox.com/toolbar/add?u={url}&t={title}&desc={text}',
            bookmerkende: 'https://www.bookmerken.de/?url={url}&title={title}',
            box: 'https://www.box.net/api/1.0/import?import_as=link&url={url}&name={title}&description={text}',
            bryderi: 'https://bryderi.se/add.html?u={url}',
            buddymarks: 'https://buddymarks.com/add_bookmark.php?bookmark_title={title}&bookmark_url={url}&bookmark_desc={text}',
            camyoo: 'https://www.camyoo.com/note.html?url={url}',
            care2: 'https://www.care2.com/news/compose?sharehint=news&share[share_type]news&bookmarklet=Y&share[title]={title}&share[link_url]={url}&share[content]={text}',
            citeulike: 'https://www.citeulike.org/posturl?url={url}&title={title}',
            classicalplace: 'https://www.classicalplace.com/?u={url}&t={title}&c={text}',
            cosmiq: 'https://www.cosmiq.de/lili/my/add?url={url}',
            diggita: 'https://www.diggita.it/submit.php?url={url}&title={title}',
            diigo: 'https://www.diigo.com/post?url={url}&title={title}&desc={text}',
            domelhor: 'https://domelhor.net/submit.php?url={url}&title={title}',
            dotnetshoutout: 'https://dotnetshoutout.com/Submit?url={url}&title={title}',
            douban: 'https://www.douban.com/recommend/?url={url}&title={title}',
            dropjack: 'https://www.dropjack.com/submit.php?url={url}',
            edelight: 'https://www.edelight.de/geschenk/neu?purl={url}',
            ekudos: 'https://www.ekudos.nl/artikel/nieuw?url={url}&title={title}&desc={text}',
            elefantapl: 'https://elefanta.pl/member/bookmarkNewPage.action?url={url}&title={title}&bookmarkVO.notes=',
            embarkons: 'https://www.embarkons.com/sharer.php?u={url}&t={title}',
            evernote: 'https://www.evernote.com/clip.action?url={url}&title={title}',
            extraplay: 'https://www.extraplay.com/members/share.php?url={url}&title={title}&desc={text}',
            ezyspot: 'https://www.ezyspot.com/submit?url={url}&title={title}',
            fabulously40: 'https://fabulously40.com/writeblog?subject={title}&body={url}',
            informazione: 'https://fai.informazione.it/submit.aspx?url={url}&title={title}&desc={text}',
            fark: 'https://www.fark.com/cgi/farkit.pl?u={url}&h={title}',
            farkinda: 'https://www.farkinda.com/submit?url={url}',
            favable: 'https://www.favable.com/oexchange?url={url}&title={title}&desc={text}',
            favlogde: 'https://www.favlog.de/submit.php?url={url}',
            flaker: 'https://flaker.pl/add2flaker.php?title={title}&url={url}',
            folkd: 'https://www.folkd.com/submit/{url}',
            fresqui: 'https://fresqui.com/enviar?url={url}',
            friendfeed: 'https://friendfeed.com/share?url={url}&title={title}',
            funp: 'https://funp.com/push/submit/?url={url}',
            fwisp: 'https://fwisp.com/submit.php?url={url}',
            givealink: 'https://givealink.org/bookmark/add?url={url}&title={title}',
            gmail: 'https://mail.google.com/mail/?view=cm&fs=1&to=&su={title}&body={text}&ui=1',
            goodnoows: 'https://goodnoows.com/add/?url={url}',
            google: 'https://www.google.com/bookmarks/mark?op=add&bkmk={url}&title={title}&annotation={text}',
            googletranslate: 'https://translate.google.com/translate?hl=en&u={url}&tl=en&sl=auto',
            greaterdebater: 'https://greaterdebater.com/submit/?url={url}&title={title}',
            hackernews: 'https://news.ycombinator.com/submitlink?u={url}&t={title}',
            hatena: 'https://b.hatena.ne.jp/bookmarklet?url={url}&btitle={title}',
            hedgehogs: 'https://www.hedgehogs.net/mod/bookmarks/add.php?address={url}&title={title}',
            hotmail: 'https://www.hotmail.msn.com/secure/start?action=compose&to=&subject={title}&body={{content}}',
            w3validator: 'https://validator.w3.org/check?uri={url}&charset=%28detect+automatically%29&doctype=Inline&group=0',
            ihavegot: 'https://www.ihavegot.com/share/?url={url}&title={title}&desc={text}',
            instapaper: 'https://www.instapaper.com/edit?url={url}&title={title}&summary={text}',
            isociety: 'https://isociety.be/share/?url={url}&title={title}&desc={text}',
            iwiw: 'https://iwiw.hu/pages/share/share.jsp?v=1&u={url}&t={title}',
            jamespot: 'https://www.jamespot.com/?action=spotit&u={url}&t={title}',
            jumptags: 'https://www.jumptags.com/add/?url={url}&title={title}',
            kaboodle: 'https://www.kaboodle.com/grab/addItemWithUrl?url={url}&pidOrRid=pid=&redirectToKPage=true',
            kaevur: 'https://kaevur.com/submit.php?url={url}',
            kledy: 'https://www.kledy.de/submit.php?url={url}&title={title}',
            librerio: 'https://www.librerio.com/inbox?u={url}&t={title}',
            linkuj: 'https://linkuj.cz?id=linkuj&url={url}&title={title}&description={text}&imgsrc=',
            livejournal: 'https://www.livejournal.com/update.bml?subject={title}&event={url}',
            logger24: 'https://logger24.com/?url={url}',
            mashbord: 'https://mashbord.com/plugin-add-bookmark?url={url}',
            meinvz: 'https://www.meinvz.net/Suggest/Selection/?u={url}&desc={title}&prov=addthis.com',
            mekusharim: 'https://mekusharim.walla.co.il/share/share.aspx?url={url}&title={title}',
            memori: 'https://memori.ru/link/?sm=1&u_data[url]={url}',
            meneame: 'https://www.meneame.net/submit.php?url={url}',
            mixi: 'https://mixi.jp/share.pl?u={url}',
            moemesto: 'https://moemesto.ru/post.php?url={url}&title={title}',
            myspace: 'https://www.myspace.com/Modules/PostTo/Pages/?u={url}&t={title}&c=',
            n4g: 'https://www.n4g.com/tips.aspx?url={url}&title={title}',
            netlog: 'https://www.netlog.com/go/manage/links/?view=save&origin=external&url={url}&title={title}&description={text}',
            netvouz: 'https://netvouz.com/action/submitBookmark?url={url}&title={title}&popup=no&description={text}',
            newstrust: 'https://newstrust.net/submit?url={url}&title={title}&ref=addthis',
            newsvine: 'https://www.newsvine.com/_tools/seed&save?u={url}&h={title}&s={text}',
            nujij: 'https://nujij.nl/jij.lynkx?u={url}&t={title}&b={text}',
            oknotizie: 'https://oknotizie.virgilio.it/post?title={title}&url={url}',
            oyyla: 'https://www.oyyla.com/gonder?phase=2&url={url}',
            pdfonline: 'https://savepageaspdf.pdfonline.com/pdfonline/pdfonline.asp?cURL={url}',
            pdfmyurl: 'https://pdfmyurl.com?url={url}',
            phonefavs: 'https://phonefavs.com/bookmarks?action=add&address={url}&title={title}',
            plaxo: 'https://www.plaxo.com/events?share_link={url}&desc={text}',
            plurk: 'https://www.plurk.com/m?content={url}+({title})&qualifier=shares ',
            posteezy: 'https://posteezy.com/node/add/story?title={title}&body={url}',
            pusha: 'https://www.pusha.se/posta?url={url}&title={title}&description={text}',
            rediff: 'https://share.rediff.com/bookmark/addbookmark?title={title}&bookmarkurl={url}',
            redkum: 'https://www.redkum.com/add?url={url}&step=1&title={title}',
            scoopat: 'https://scoop.at/submit?url={url}&title={title}&body={text}',
            sekoman: 'https://sekoman.lv/home?status={title}&url={url}',
            shaveh: 'https://shaveh.co.il/submit.php?url={url}&title={title}',
            shetoldme: 'https://shetoldme.com/publish?url={url}&title={title}&body={text}',
            sinaweibo: 'https://v.t.sina.com.cn/share/share.php?url={url}&title={title}',
            sodahead: 'https://www.sodahead.com/news/submit/?url={url}&title={title}',
            sonico: 'https://www.sonico.com/share.php?url={url}&title={title}',
            springpad: 'https://springpadit.com/s?type=lifemanagr.Bookmark&url={url}&name={title}',
            startaid: 'https://www.startaid.com/index.php?st=AddBrowserLink&type=Detail&v=3&urlname={url}&urltitle={title}&urldesc={text}',
            startlap: 'https://www.startlap.hu/sajat_linkek/addlink.php?url={url}&title={title}',
            studivz: 'https://www.studivz.net/Suggest/Selection/?u={url}&desc={title}&prov=addthis.com',
            stuffpit: 'https://www.stuffpit.com/add.php?produrl={url}',
            stumpedia: 'https://www.stumpedia.com/submit?url={url}',
            svejo: 'https://svejo.net/story/submit_by_url?url={url}&title={title}&summary={text}',
            symbaloo: 'https://www.symbaloo.com/en/add/?url={url}&title={title}',
            thewebblend: 'https://thewebblend.com/submit?url={url}&title={title}',
            thinkfinity: 'https://www.thinkfinity.org/favorite-bookmarklet.jspa?url={url}&subject={title}',
            thisnext: 'https://www.thisnext.com/pick/new/submit/url/?description={text}&name={title}&url={url}',
            tuenti: 'https://www.tuenti.com/share?url={url}',
            typepad: 'https://www.typepad.com/services/quickpost/post?v=2&qp_show=ac&qp_title={title}&qp_href={url}&qp_text={text}',
            viadeo: 'https://www.viadeo.com/shareit/share/?url={url}&title={title}&urlaffiliate=32005&encoding=UTF-8',
            virb: 'https://virb.com/share?external&v=2&url={url}&title={title}',
            visitezmonsite: 'http://www.visitezmonsite.com/publier?url={url}&title={title}&body={text}',
            vybralisme: 'https://vybrali.sme.sk/sub.php?url={url}',
            webnews: 'https://www.webnews.de/einstellen?url={url}&title={title}',
            wirefan: 'https://www.wirefan.com/grpost.php?d=&u={url}&h={title}&d={text}',
            wordpress: 'https://wordpress.com/wp-admin/press-this.php?u={url}&t={title}&s={text}&v=2',
            wowbored: 'https://www.wowbored.com/submit.php?url={url}',
            wykop: 'https://www.wykop.pl/dodaj?url={url}&title={title}&desc={text}',
            yahoobkm: 'https://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u={url}&t={title}&d={text}',
            yahoomail: 'https://compose.mail.yahoo.com/?To=&Subject={title}&body={{content}}',
            yammer: 'httpss://www.yammer.com/home/bookmarklet?bookmarklet_pop=1&u={url}&t={title}',
            yardbarker: 'http://www.yardbarker.com/author/new/?pUrl={url}&pHead={title}',
            yigg: 'https://www.yigg.de/neu?exturl={url}&exttitle={title}&extdesc={text}',
            yoolink: 'https://go.yoolink.to/addorshare?url_value={url}&title={title}',
            yorumcuyum: 'https://www.yorumcuyum.com/?baslik={title}&link={url}',
            youmob: 'https://youmob.com/mobit.aspx?title={title}&mob={url}',
            zakladoknet: 'http://zakladok.net/link/?u={url}&t={title}',
            ziczac: 'https://ziczac.it/a/segnala/?gurl={url}&gtit={title}'
        }

        function link(network){
            var url = templates[network];
            url = url.replace('{url}', encodeURIComponent(options.url));
            url = url.replace('{title}', encodeURIComponent(options.title));
            url = url.replace('{text}', encodeURIComponent(options.text));
            url = url.replace('{image}', encodeURIComponent(options.image));
            return url;
        }

        this.each(function(i, elem){
            var classlist = get_class_list(elem);
            for(var i = 0; i < classlist.length; i++){
                var cls = classlist[i];
                if(cls.substr(0, class_prefix_length) == options.class_prefix && templates[cls.substr(class_prefix_length)]){
                    var final_link = link(cls.substr(class_prefix_length));
                    $(elem).attr('href', final_link).click(function(){
                        var screen_width = screen.width;
                        var screen_height = screen.height;
                        var popup_width = options.width ? options.width : (screen_width - (screen_width*0.2));
                        var popup_height = options.height ? options.height : (screen_height - (screen_height*0.2));
                        var left = (screen_width/2)-(popup_width/2);
                        var top = (screen_height/2)-(popup_height/2);
                        var parameters = 'toolbar=0,status=0,width=' + popup_width + ',height=' + popup_height + ',top=' + top + ',left=' + left;
                        return window.open($(this).attr('href'), '', parameters) && false;
                    });
                }
            }
        });
    }

    // $.fn.ShareCounter = function(options){
    //     var defaults = {
    //         url: window.location.href,
    //         class_prefix: 'c_',
    //         display_counter_from: 0
    //     };

    //     var options = $.extend({}, defaults, options);

    //     var class_prefix_length = options.class_prefix.length;

    //     var social = {
    //         'facebook': facebook,
    //         'vk': vk,
    //         'myworld': myworld,
    //         'linkedin': linkedin,
    //         'odnoklassniki': odnoklassniki,
    //         'pinterest': pinterest,
    //         'plus': plus
    //     }

    //     var shares = 0;

    //     this.each(function(i, elem){
    //         var classlist = get_class_list(elem);
    //         for(var i = 0; i < classlist.length; i++){
    //             var cls = classlist[i];
    //             if(cls.substr(0, class_prefix_length) == options.class_prefix && social[cls.substr(class_prefix_length)]){
    //                 social[cls.substr(class_prefix_length)](options.url, function(count){
    //                     if (count >= options.display_counter_from){
    //                         $(elem).text(count);
    //                         shares = parseInt(shares) + parseInt(count);
    //                         $('.total_count').html(shares/ 2);
    //                     }
    //                 })
    //             }
    //         }
    //     });

    //     function facebook(url, callback){
    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'jsonp',
    //             //url: 'https://api.facebook.com/restserver.php', // deprecated
    //             url: 'https://graph.facebook.com/?id=' + url,
    //             //data: {'method': 'links.getStats', 'urls': [url], 'format': 'json'}
    //         })
    //             .done(function (data){
    //                 //callback(parseInt(data[0].share_count)+160) // deprecated
    //                 if(!$.isEmptyObject(data.share)){
    //                     callback(data.share.share_count+160)
    //                 }
    //             })
    //             .fail(function(){callback(0);})
    //     }

    //     function vk(url, callback){
    //         if(window.VK === undefined){VK = {};}

    //         VK.Share = {
    //             count: function(idx, value){
    //                 callback(value);
    //             }
    //         }

    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'jsonp',
    //             url: 'https://vk.com/share.php',
    //             data: {'act': 'count', 'index': 0, 'url': url}
    //         })
    //             .fail(function(data, status){
    //                 if(status != 'parsererror'){
    //                     callback(0);
    //                 }
    //             })
    //     }

    //     function myworld(url, callback){
    //         var results = [];
    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'jsonp',
    //             url: 'https://connect.mail.ru/share_count',
    //             jsonp: 'func',
    //             data: {'url_list': url, 'callback': '1'}
    //         })
    //             .done(function(data){callback(data[url].shares)})
    //             .fail(function(data){callback(0)})
    //     }

    //     function linkedin(url, callback){
    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'jsonp',
    //             url: 'https://www.linkedin.com/countserv/count/share',
    //             data: {'url': url, 'format': 'jsonp'}
    //         })
    //             .done(function(data){callback(data.count)})
    //             .fail(function(){callback(0)})
    //     }

    //     function odnoklassniki(url, callback){

    //         ODKL = {
    //             updateCount: function(param1, value){
    //                 callback(value);
    //             }
    //         }

    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'jsonp',
    //             url: 'https://ok.ru/dk',
    //             data: {'st.cmd': 'extLike', 'ref': url}
    //         })
    //             .fail(function(data, status){
    //                 if(status != 'parsererror'){
    //                     callback(0);
    //                 }
    //             })
    //     }

    //     function pinterest(url, callback){
    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'jsonp',
    //             url: 'https://api.pinterest.com/v1/urls/count.json',
    //             data: {'url': url}
    //         })
    //             .done(function(data){callback(data.count)})
    //             .fail(function(){callback(0)})
    //     }

    //     function plus(url, callback){
    //         $.ajax({
    //             type: 'POST',
    //             url: 'https://clients6.google.com/rpc',
    //             processData: true,
    //             contentType: 'application/json',
    //             data: JSON.stringify({
    //                 'method': 'pos.plusones.get',
    //                 'id': location.href,
    //                 'params': {
    //                     'nolog': true,
    //                     'id': url,
    //                     'source': 'widget',
    //                     'userId': '@viewer',
    //                     'groupId': '@self'
    //                 },
    //                 'jsonrpc': '2.0',
    //                 'key': 'p',
    //                 'apiVersion': 'v1'
    //             })
    //         })
    //             .done(function(data){callback(data.result.metadata.globalCounts.count)})
    //             .fail(function(){callback(0)})

    //     }

    // }

})(jQuery);