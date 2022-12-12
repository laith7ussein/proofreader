
jQuery(($) => {

    String.prototype.replaceAt = function (index, replacement) {
        return this.substring(0, index) + replacement + this.substring(index + replacement.length);
    }

    const prrofreaderFn = (html, dots = true) => {

        if (!html) return '';

        const x = html;

        // if ( dots ) {
        //     html = html.replaceAll(/(.{50,}[^\.])/g, '$1.');
        // }

        // html = html.replaceAll(/\.+$/g, '.');
        // html = html.replaceAll(/(\s\و)\s/g, '$1');
        // html = html.replaceAll(/([\،\,\.\؛\;\:\:])([a-zA-Zء-ي]+)/g, '$1 $2');



        // html = html.replaceAll(/([a-zA-Zء-ي]+)\s([\،\,\.\؛\;\:\:])/g, '$1$2');

        // pranthesis
        // html = html.replaceAll(/([ء-ي]+)\s(\))/g, '$1$2');
        // html = html.replaceAll(/([a-zA-Z]+)\s(\))/g, '$1$2');
        // html = html.replaceAll(/(\()\s([ء-ي]+)/g, '$1$2');
        // html = html.replaceAll(/(\()\s([a-zA-Z]+)/g, '$1$2');
        // html = html.replaceAll(/(\))([a-zA-Zء-ي])/g, '$1 $2');
        // html = html.replaceAll(/([a-zA-Zء-ي])(\()/g, '$1 $2');

        // words
        // if (typeof proofreader_words === typeof []) {
        //     Object.keys(proofreader_words).forEach(function (wordFull) {
        //         const replacement = proofreader_words[wordFull];
        //         const wordsSplitted = wordFull.split('-');
        //         wordsSplitted.forEach(function (word) {

        //             word = word.trim().replace(/([()])/g, '\\$1');
        //             const regex = new RegExp(`([\\s\\و]|^)${word}([\\،\\s\\.\\,\\:\\؛\\;\\"\\(\\)\\'\\?\\!]|$)`, 'g');

        //             html = html.replaceAll(regex, `$1${replacement}$2`);

        //         });
        //     });
        // } else alert('No words found');

        // double quotes
        // if (html.length) {
        //     let quotesCount = 0;
        //     let newHtml = html.split('');
        //     newHtml.forEach(function (char, index) {
        //         if (char === '"') {
        //             quotesCount += 1;
        //             switch ( quotesCount % 2 ) {
        //                 case 0:
        //                     if (html[index - 1] === ' ') newHtml[index - 1] = '';
        //                     if (html[index + 1] !== ' ' && typeof html[index + 1] !== typeof undefined && html[index + 1] !== '.' && html[index + 1] !== ',' && html[index + 1] !== '،' && html[index + 1] !== ':' && html[index + 1] !== ';') newHtml[index + 1] = ' '+newHtml[index + 1];
        //                     if (html[index + 1] === ' ' && typeof html[index + 2] !== typeof undefined && (html[index + 2] === '.' || html[index + 2] === ',' || html[index + 2] === '،' || html[index + 2] === ':' || html[index + 2] === ';')) newHtml[index + 1] = '';
        //                     break;
        //                 case 1:
        //                     if (typeof html[index - 1] !== typeof undefined && html[index - 1] !== ' ' && html[index - 1] !== 'و' && html[index - 1] !== 'ـ') newHtml[index - 1] = newHtml[index - 1]+' ';
        //                     if (html[index + 1] === ' ') newHtml[index + 1] = '';
        //             }
        //         }
        //     });
        //     html = newHtml.join('');
        // }

        // return x;
        return html;

    }

    const prrofreader_mark_errors = (html) => {

        if (!html) return '';

        html = html.replaceAll('<proofreader-mark style="background-color: #ffe2e2; border-bottom: 4px solid#ff1919;">', '');
        html = html.replaceAll('</proofreader-mark>', '');
        const m = [
            ...html.matchAll(/([a-zA-Zء-ي]+)\s([\،\,\.\؛\;\:\:])/g),
        ];
        m.forEach(function (match) {
            const matchCase = match[0];
            html = html.replace(matchCase, `<proofreader-mark style="background-color: #ffe2e2; border-bottom: 4px solid#ff1919;">${matchCase}</proofreader-mark>`);
        });

        return html;

    }

    tinymce.PluginManager.add('proofreader_mce_button', function (editor, url) {

        // setInterval()
        console.log(editor);
        editor.on('input', function (e) {
            // const contentBodyBase = editor.getBody();
            // $(contentBodyBase).find('#cursor').remove();
            // editor.execCommand('mceInsertContent', false, `<span id=\"cursor\"/>`);
            $(editor.getBody()).find('[cursor]').removeAttr('cursor');
            $(editor.selection.getNode()).attr('cursor', '');
            // console.log(editor.selection.getNode());
            // const rng = editor.selection.getRng().startOffset;
            const originalRange = jQuery.extend(true, {}, editor.selection.getRng());
            const contentBody = editor.getBody();
            $(contentBody).html(prrofreader_mark_errors($(contentBody).html()));
            // editor.selection.setCursorLocation($('[cursor]')[0], editor.selection.getRng().startOffset);
            // console.log($(editor.getBody()).find('[cursor]')[0]);
            editor.selection.select($(editor.getBody()).find('[cursor]')[0]);
            editor.selection.collapse(false);
            editor.excuteCommand('mceInsertContent', false, `<span id=\"cursor\"/>`);

            // editor.selection.setCursorLocation($(editor.getBody()).find('[cursor]')[0], rng);
            // editor.selection.select(editor.dom.select('o')[0]);
        });

        editor.addButton('proofreader_mce_button', {
            onclick: function () {

                const contentBody = editor.getBody();

                // $(contentBody).html(prrofreader_mark_errors($(contentBody).html()));

                $(contentBody).html(
                    $(contentBody).html().replaceAll(/<br>\\*/g, "</p><p>").replaceAll('&nbsp;', '')
                );

                $(contentBody).find('p, li, h1, h2, h3, h4, h5, h6, strong, b, span').each(function (index, element) {
                    const $element = $(element);
                    if ($element.text() === '' && $element.find('img, a, div').length === 0) {
                        $element.remove();
                    } else {

                        if ($element.find('img, a').length) return;

                        $element.html(
                            prrofreaderFn(
                                $element.html()
                            )
                        );

                    }
                });

                $('[name="post_title"]').val(
                    prrofreaderFn(
                        $('[name="post_title"]').val()
                        , false)
                )

                $('[name="tie_post_sub_title"]').val(
                    prrofreaderFn(
                        $('[name="tie_post_sub_title"]').val()
                        , false)
                )

                $(contentBody).find('p').filter(function () {
                    return $(this).text().length < 50
                }).replaceWith(function () {
                    return `<h4>${this.innerHTML}</h4>`;
                });

            },
            icon: 'proofreader-icon',
            image: 'https://img.icons8.com/pastel-glyph/512/checked--v3.png',
            tooltip: 'تدقيق النص',
        });
    });

}, 'jquery');