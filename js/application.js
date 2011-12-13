var see = {
    anim_speed: 450,
    url: base_url,
    language: lang,
    masonry: function () {
        $('.content').masonry({
            singleMode: true
        });
        return see
    },
    hash: function (a) {
        if (a) window.location.hash = a;
        else return window.location.hash
    },
    where_am_i: function () {
        if (see.hash()) {
            var a = see.hash();
            var b = a.replace('#', '').replace('week-', '');
            if ($('.box[week="' + b + '"]').length) {
                $('.container').css('display', 'none');
                see.make_week_active($('.box[week="' + b + '"]'));
                see.load_week(b)
            } else {
                if (b == 'credits') {
                    see.load_page('misc/credits')
                } else if (b == 'how-it-works') {
                    see.load_page('misc/howitworks')
                } else if (b == 'drop-a-line') {
                    see.load_page('misc/drop')
                } else if (b == 'api-userguide') {
                    see.load_page('api/userguide/')
                }
            }
        }
    },
    fade_intro: function () {
        $('#the_white_thing').fadeOut(see.anim_speed);
        return see
    },
    email_valid: function (a) {
        var b = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
        return b.test(a)
    },
    load_lightbox: function () {
        var a = $('.ltbx');
        if (a.length) {
            var b = see.url + 'widgets/lightbox/';
            var c = b + 'lightbox-btn-';
            a.lightBox({
                imageBtnPrev: c + 'prev.gif',
                imageBtnNext: c + 'next.gif',
                imageBtnClose: c + 'close.gif',
                imageLoading: b + 'lightbox-ico-loading.gif',
                imageBlank: b + 'lightbox-blank.gif',
            })
        }
        return see
    },
    xhtml: {
        loader: '<div class="loader_roll"></div>'
    },
    bind_click: function (a) {
        if (a == 'weeks') {
            $('.box').click(function () {
                see.load_week($(this).attr('week'));
                see.make_week_active($(this))
            })
        }
        return see
    },
    load_week: function (b) {
        if (b == 'undefined') return;
        $('.container').html(see.xhtml.loader).css('display', 'block');
        see.viewport.top();
        if (b == 'next-week') {
            see.hash('next-week');
            b = 'next/ajax'
        } else if (b == 'next-week-form') {
            see.hash('next-week-form');
            b = 'next/ajax-form'
        } else {
            see.hash('week-' + b)
        }
        see.track('Ajax-Weeks', 'Week-' + b, 'Week request via ajax');
        $.post(see.url + 'week/' + b + '/' + see.language, {
            set_language: see.language,
            key: $('.box[week="' + b + '"]').attr('key')
        }, function (a) {
            $('.container').html(a);
            see.masonry()
        })
    },
    load_current_week: function () {
        var a = $('.box:eq(1)').attr('week');
        see.load_week(a)
    },
    load_page: function (a) {
        $('.box').removeClass('active');
        $('.container').html(see.xhtml.loader).css('display', 'block').load(see.url + a);
        see.track('Ajax-Pages', a, 'Page request via ajax')
    },
    like_photo: function (b) {
        b = $(b);
        if (!b.hasClass('active')) {
            b.addClass('active');
            $.post(see.url + 'i/like/this', {
                photo: b.attr('photoid')
            }, function (a) {
                if (a == 1) b.html(parseInt(b.html()) + 1)
            })
        }
        return false
    },
    make_week_active: function (a) {
        $('.box').removeClass('active');
        a.addClass('active')
    },
    next_week_check: function () {
        return form_send()
    },
    viewport: {
        top: function () {
            $('html,body').animate({
                scrollTop: 0
            }, 600)
        }
    },
    forms: {
        drop: {
            send: function () {
                var b = $('input[name=uname]').val();
                var c = $('input[name=email]').val();
                var d = $('textarea[name=message]').val();
                $('.container').html(see.xhtml.loader).css('display', 'block');
                $.post(see.url + 'misc/drop', {
                    name: b,
                    email: c,
                    message: d
                }, function (a) {
                    $('.container').html(a)
                })
            }
        }
    },
    show_social: function (a) {
        $('#social_pages .asocial').fadeOut(0);
        $('#social_pages .' + a).fadeIn(0);
        $('#social_pages').fadeIn(see.anim_speed);
        see.track('Socials', a, 'Click on the little icon')
    },
    fade_social: function () {
        $('#social_pages').fadeOut(see.anim_speed, function () {
            $('#social_pages .asocial').css('display', 'none')
        })
    },
    track: function (a, b, c) {
        if (window._gaq !== undefined) _gaq.push(['_trackEvent', a, b, c]);
        return see
    }
};
see.where_am_i();
$(document).ready(function () {
    $('.container').css('display', 'block');
    see.masonry().bind_click('weeks').load_lightbox()
});