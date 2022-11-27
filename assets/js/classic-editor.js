
jQuery(($) => {

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

                $(contentBody).find('p').each(function (index, element) {
                    const $element = $(element);
                    if ($element.text() === '') {
                        $element.remove();
                    } else {
                        $element.html(
                            $element.html().replaceAll(/(.{50,}[^\.])/g, '$1.')
                        );
                        $element.html(
                            $element.html().replaceAll(/\.+$/g, '.')
                        );
                        $element.html(
                            $element.html().replaceAll(/(\s\و)\s/g, '$1')
                        );
                        $element.html(
                            $element.html().replaceAll(/(.+)\s([\،\,\.\؛\;\:])/g, '$1$2')
                        );

                        // pranthesis
                        $element.html($element.html().replaceAll(/([ء-ي]+)\s(\))/g, '$1$2'));
                        $element.html($element.html().replaceAll(/([a-zA-Z]+)\s(\))/g, '$1$2'));
                        $element.html($element.html().replaceAll(/(\()\s([ء-ي]+)/g, '$1$2'));
                        $element.html($element.html().replaceAll(/(\()\s([a-zA-Z]+)/g, '$1$2'));
                        $element.html($element.html().replaceAll(/(\))([a-zA-Zء-ي])/g, '$1 $2'));
                        $element.html($element.html().replaceAll(/([a-zA-Zء-ي])(\()/g, '$1 $2'));

                        // words
                        if ( typeof proofreader_words === typeof [] ) {
                            Object.keys(proofreader_words).forEach(function (word) {
                                const replacement = proofreader_words[word];
                                $element.html($element.html().replaceAll(word, replacement));
                            });
                        } else {
                            alert('No words found');
                        }

                    }
                });

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