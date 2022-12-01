
jQuery(($) => {

    const prrofreaderFn = (html) => {
        html = html.replaceAll(/(.{50,}[^\.])/g, '$1.');
        html = html.replaceAll(/\.+$/g, '.');
        html = html.replaceAll(/(\s\و)\s/g, '$1');
        html = html.replaceAll(/([\،\,\.\؛\;\:\:])([a-zA-Zء-ي]+)/g, '$1 $2');
        html = html.replaceAll(/([a-zA-Zء-ي]+)\s([\،\,\.\؛\;\:\:])/g, '$1$2');

        // pranthesis
        html = html.replaceAll(/([ء-ي]+)\s(\))/g, '$1$2');
        html = html.replaceAll(/([a-zA-Z]+)\s(\))/g, '$1$2');
        html = html.replaceAll(/(\()\s([ء-ي]+)/g, '$1$2');
        html = html.replaceAll(/(\()\s([a-zA-Z]+)/g, '$1$2');
        html = html.replaceAll(/(\))([a-zA-Zء-ي])/g, '$1 $2');
        html = html.replaceAll(/([a-zA-Zء-ي])(\()/g, '$1 $2');

        // words
        if (typeof proofreader_words === typeof []) {
            Object.keys(proofreader_words).forEach(function (wordFull) {
                const replacement = proofreader_words[wordFull];
                const wordsSplitted = wordFull.split('-');
                wordsSplitted.forEach(function (word) {
                    word = word.trim();
                    const regex = new RegExp(`([\\s\\و])${word}([\\s\\.\\,\\:\\;\\"\\(\\)\\'\\?\\!])`, 'g');
                    html = html.replaceAll(regex, `$1${replacement}$2`)
                });
            });
        } else {
            alert('No words found');
        }

        return html;
    }

    tinymce.PluginManager.add('proofreader_mce_button', function (editor, url) {
        console.log(editor);
        editor.addButton('proofreader_mce_button', {
            onclick: function () {

                const contentBody = editor.getBody();

                // $(contentBody).find('div[dir="auto"]').replaceWith(function () {
                //     return `<p>${this.innerHTML}</p>`;
                // });


                $(contentBody).html(
                    $(contentBody).html().replaceAll(/<br>\\*/g, "</p><p>").replaceAll('&nbsp;', '')
                );

                $(contentBody).find('p, li, h1, h2, h3, h4, h5, h6').each(function (index, element) {
                    const $element = $(element);
                    if ($element.text() === '' && $element.children().length === 0) {
                        $element.remove();
                    } else {
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
                    )
                )

                $('[name="tie_post_sub_title"]').val(
                    prrofreaderFn(
                        $('[name="tie_post_sub_title"]').val()
                    )
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