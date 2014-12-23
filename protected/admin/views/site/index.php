<?php 
$this->layout='none';
Yii::app()->clientScript->registerCoreScript('jquery');

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo Yii::app()->name ?> - 管理中心</title>
<link rel="stylesheet" href="/images/admin/admincp.css" type="text/css" media="all" />
</head>
<body style="margin: 0px" scroll="no">
<div id="append_parent"></div>
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td colspan="2" height="90"><div class="mainhd">
        <div class="logo">Administrator's Control Panel</div>
        <div class="uinfo">
          <p>您好, <em><?php echo Yii::app()->user->name ?></em>,<em><?php echo $user->roleName; ?></em> | [ <a href="<?php echo $this->createUrl('site/logout') ?>" target="_top">退出</a> ]</p>
          <p class="btnlink"><a href="<?php echo $this->createUrl('site/profile') ?>" target="main">个人资料</a></p>
        </div>
        <div class="navbg"></div>
        <div class="nav">
          <ul id="topmenu">
          <?php foreach($menu as $name => $config): ?>
            <li><em><a href="javascript:;" id="header_<?php echo $name ?>" hidefocus="true" onMouseOver="previewheader('<?php echo $name ?>')" onMouseOut="previewheader()"  onclick="toggleMenu('<?php echo $name ?>', '<?php foreach($config['submenu'] as $label => $url){echo $url;break;} ?>');"><?php echo $config['name']; ?></a></em></li>
          <?php endforeach; ?>
          </ul>
          <div class="currentloca">
            <p id="admincpnav"></p>
          </div>
          <div class="navbd"></div>
          <div class="sitemapbtn">
               
          </div>
        </div>
      </div></td>
  </tr>
  <tr>
    <td valign="top" width="160" class="menutd"><div id="leftmenu" class="menu">
     <?php foreach($menu as $key => $config): ?>
        <ul id="menu_<?php echo $key ?>" style="display: none">
        <?php foreach($config['submenu'] as $label => $url): ?>
          <li><a href="<?php echo $url; ?>" hidefocus="true" target="main"><?php echo $label ?></a></li>
        <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
       
      </div></td>
    <td valign="top" width="100%" class="mask" id="mainframes"><iframe src="<?php echo $this->createUrl('site/welcome') ?>" id="main" name="main" onload="mainFrame(0)" width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow: visible;display:"></iframe></td>
  </tr>
</table>
<div id="scrolllink" style="display: none"> <span onClick="menuScroll(1)"><img src="/images/admin/scrollu.gif" /></span> <span onClick="menuScroll(2)"><img src="/images/admin/scrolld.gif" /></span> </div>

<script type="text/JavaScript" charset="utf-8">
    var headers = new Array('<?php echo implode("', '",array_keys($menu)); ?>');
    var admincpfilename = 'admin.php';
    var menukey = '', custombarcurrent = 0;
    function toggleMenu(key, url) {
        if(key == 'index' && url == 'home') {
            if(BROWSER.ie) {
                doane(event);
            }
            parent.location.href = admincpfilename + '?frames=yes';
            return false;
        }
        menukey = key;
        for(var k in headers) {
            if($('#menu_' + headers[k])[0]) {
                $('#menu_' + headers[k])[0].style.display = headers[k] == key ? '' : 'none';
            }
        }
        $('#menu_paneladd').css("display", "none");
        var lis = $('#topmenu LI');
        for(var i = 0; i < lis.length; i++) {
            if(lis[i].className == 'navon') lis[i].className = '';
        }
        $('#header_' + key)[0].parentNode.parentNode.className = 'navon';
        if(url) {
            parent.mainFrame(0);
            parent.main.location = url;
            var hrefs = $('#menu_' + key + ' A');
            for(var j = 0; j < hrefs.length; j++) {
                hrefs[j].className = hrefs[j].href == url ? 'tabon' : (hrefs[j].className == 'tabon' ? '' : hrefs[j].className);
            }
        }
        setMenuScroll();
        return false;
    }
    var headerST = null;
    function previewheader(key) {
        if($("#menu_paneladd").css("display") != "none") {
            return;
        }
        if(key) {
            headerST = setTimeout(function() {
                for(var k in headers) {
                    if($('#menu_' + headers[k])[0]) {
                        $('#menu_' + headers[k])[0].style.display = headers[k] == key ? '' : 'none';
                    }
                }
                var hrefs = $('#menu_' + key)[0].getElementsByTagName('a');
                for(var j = 0; j < hrefs.length; j++) {
                    hrefs[j].className = '';
                }
            }, 1000);
        } else {
            clearTimeout(headerST);
        }
    }

    function setMenuScroll() {
        var obj = $('#menu_' + menukey)[0];
        if (!obj) return;
        var scrollh = document.body.offsetHeight - 160;
        obj.style.overflow = 'visible';
        obj.style.height = '';
        $('#scrolllink')[0].style.display = 'none';
        if(obj.offsetHeight + 150 > document.body.offsetHeight && scrollh > 0) {
            obj.style.overflow = 'hidden';
            obj.style.height = scrollh + 'px';
            $('#scrolllink')[0].style.display = '';
        }
    }
    function menuScroll(op, e) {
        var obj = $('#menu_' + menukey)[0];
        var scrollh = document.body.offsetHeight - 160;
        if(op == 1) {
            obj.scrollTop = obj.scrollTop - scrollh;
        } else if(op == 2) {
            obj.scrollTop = obj.scrollTop + scrollh;
        } else if(op == 3) {
            if(!e) e = window.event;
            if(e.wheelDelta <= 0 || e.detail > 0) {
                obj.scrollTop = obj.scrollTop + 20;
            } else {
                obj.scrollTop = obj.scrollTop - 20;
            }
        }
    }
    function initCpMenus(menuContainerid) {
        var key = '';
        var hrefs = $("#"+menuContainerid+ " A");
        for(var i = 0; i < hrefs.length; i++) {
            if(menuContainerid == 'leftmenu' && !key && 'action=index'.indexOf(hrefs[i].href.substr(hrefs[i].href.indexOf(admincpfilename + '?action=') + 12)) != -1) {
                key = hrefs[i].parentNode.parentNode.id.substr(5);
                hrefs[i].className = 'tabon';
            }
            if(!hrefs[i].getAttribute('ajaxtarget')) hrefs[i].onclick = function() {
                if(menuContainerid != 'custommenu') {
                    var lis = $("#"+menuContainerid + ' LI');
                    for(var k = 0; k < lis.length; k++) {
                        if(lis[k].firstChild.className != 'menulink') lis[k].firstChild.className = '';
                    }
                    if(this.className == '') this.className = menuContainerid == 'leftmenu' ? 'tabon' : 'bold';
                }
                if(menuContainerid != 'leftmenu') {
                    var hk, currentkey;
                    var leftmenus = $('#leftmenu A');
                    for(var j = 0; j < leftmenus.length; j++) {
                        hk = leftmenus[j].parentNode.parentNode.id.substr(5);
                        if(this.href.indexOf(leftmenus[j].href) != -1) {
                            leftmenus[j].className = 'tabon';
                            if(hk != 'index') currentkey = hk;
                        } else {
                            leftmenus[j].className = '';
                        }
                    }
                    if(currentkey) toggleMenu(currentkey);
                    hideMenu();
                }
            }
        }
        return key;
    }
    var header_key = initCpMenus('leftmenu');
    toggleMenu(header_key ? header_key : 'index');
    function initCpMap() {
        var ul, hrefs, s;
        s = '<ul class="cnote"><li><img src="static/image/admin/tn_map.gif" /></li><li> </li></ul><table class="cmlist" id="mapmenu"><tr>';

        for(var k in headers) {
            if(headers[k] != 'index' && headers[k] != 'uc') {
                s += '<td valign="top"><ul class="cmblock"><li><h4>' + $('#header_' + headers[k]).html() + '</h4></li>';
                ul = $('#menu_' + headers[k])[0];
                hrefs = ul.getElementsByTagName('a');
                for(var i = 0; i < hrefs.length; i++) {
                    s += '<li><a href="' + hrefs[i].href + '" target="' + hrefs[i].target + '" k="' + headers[k] + '">' + hrefs[i].innerHTML + '</a></li>';
                }
                s += '</ul></td>';
            }
        }
        s += '</tr></table>';
        return s;
    }
    var cmcache = false;
    
    function resetEscAndF5(e) {
        e = e ? e : window.event;
        actualCode = e.keyCode ? e.keyCode : e.charCode;
        if(actualCode == 27) {
            if($('#cpmap_menu')[0].style.display == 'none') {

            } else {
                hideMenu();
            }
        }
        if(actualCode == 116 && parent.main) {
            if(custombarcurrent) {
                parent.$('#main_' + custombarcurrent)[0].contentWindow.location.reload();
            } else {
                parent.main.location.reload();
            }
            if(document.all) {
                e.keyCode = 0;
                e.returnValue = false;
            } else {
                e.cancelBubble = true;
                e.preventDefault();
            }
        }
    }


    function mainFrame(id, src) {
        var setFrame = !id ? 'main' : 'main_' + id, exists = 0, src = !src ? '' : src;
        var obj = $('#mainframes')[0].getElementsByTagName('IFRAME');
        for(i = 0;i < obj.length;i++) {
            if(obj[i].name == setFrame) {
                exists = 1;
            }
            obj[i].style.display = 'none';
        }
        if(!exists) {
            if(BROWSER.ie) {
                frame = document.createElement('<iframe name="' + setFrame + '" id="' + setFrame + '"></iframe>');
            } else {
                frame = document.createElement('iframe');
                frame.name = setFrame;
                frame.id = setFrame;
            }
            frame.width = '100%';
            frame.height = '100%';
            frame.frameBorder = 0;
            frame.scrolling = 'yes';
            frame.style.overflow = 'visible';
            frame.style.display = 'none';
            if(src) {
                frame.src = src;
            }
            $('#mainframes')[0].appendChild(frame);
        }
        if(id) {
            custombar_set(id);
        }
        $("#"+setFrame)[0].style.display = '';
        if(!src && custombarcurrent) {
            $('#custombar_' + custombarcurrent)[0].className = '';
            custombarcurrent = 0;
        }
    }


</script>
</body>
</html>
<script type="text/JavaScript">
function closetask() {
    document.getElementById("newtask").style.display= "none";
    document.getElementById("backhold").style.display= "none";
}
</script>
</body>
</html>