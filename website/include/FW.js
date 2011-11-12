/* 
 * $FW (flux website). Contains some useful stuff like popups for asking the user to select a flux, and stuff like that
 */

$FW = {};

/**
 * this function is used to show any popup. The url is loaded inside the popup. It MUST not be a full html page, just some html elements
 */
$FW.popupUrl = function(url) {
    $.get(url,'',
        $FW.popup,
        'html');
}

/**
 * this function creates a popup containing some htmlelements.
 * It is also used as a callback for popupUrl.
 * If you want to load the elements from a url, use popupUrl.
 * Use this if you want to directly create a popup with some elements in it
 */
$FW.popup = function(htmlElements) {
    dialog = $('<div class="modal hide fade"></div>').appendTo('body');
    dialog.html(htmlElements).modal({backdrop: true});
    dialog.bind('show',function() {
        dialog.animate({
            'top': $(window).scrollTop()+300+'px'
        },200);
    });
    dialog.bind('hide',function() {
        dialog.animate({
            'top': '0px'
        },400);
    });
    dialog.modal('show');
    dialog.bind('hidden',function() {$('.modal').remove()});
}

//$FW.popupMakeUserSelectAFlux = function(callback) {
//    $FW.SelectAFluxCallback = callback;
//    $FW.popupUrl('include/popups/selectFlux.php?callback='+callback);
//}

//$FW.popupMakeUserSelectShare = function(callback,total) {
//    $FW.SelectShareCallback = callback;
//    $FW.popupUrl('include/popups/selectShare.php?total='+total);
//}

$FW.popups = {
    selectDonationMethod: {
        data: {},
        show: function(data) {
            $FW.popups.selectDonationMethod.data = data;
            $FW.popupUrl('include/popups/selectDonation.php');
        }
    },
    selectOneOfMyFluxes: {
        data: {},
        callback: function () {},
        show: function(callback) {
            $FW.popups.selectOneOfMyFluxes.callback = callback;
            $FW.popupUrl('include/popups/selectOneOfMyFluxes.php');
        }
    },
    /**
     * this function shows a popup prompting the user to select a flux, and then calls the callback(json) where json looks like:
     * {
     *      flux_id: 123123,
     *      name: 'asdfsdf'
     * }
     * 
     */
    selectAFlux: {
        data: {},
        callback: function() {},
        show: function(callback) {
            $FW.popups.selectAFlux.callback = callback;
            $FW.popupUrl('include/popups/selectFlux.php');
        }
    },
    selectShare: {
        data: {total:0},
        callback: function() {},
        show: function (callback,total) {
            $FW.popups.selectShare.callback = callback;
            $FW.popups.selectShare.data['total'] = total;
            $FW.popupUrl('include/popups/selectShare.php');
        }
    },
    editMarkdown: {
        data: {},
        callback: function() {},
        show: function (callback,data) {
            $FW.popups.editMarkdown.data = data;
            $FW.popups.editMarkdown.callback = callback;
            $FW.popupUrl('include/popups/editMarkdown.php');
        }
    }
};


//=============
// UTILITIES:
//=============

$FW.stripHtmlTags = function (string) {
    return string.replace(/(<([^>]+)>)/ig,"");
};