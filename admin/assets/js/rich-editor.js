const editorConfig = window.SunVietEditor || {};

document.querySelectorAll('form').forEach(function (form) {
    form.acceptCharset = 'UTF-8';
});

document.querySelectorAll('input[name$="_vn"], textarea[name$="_vn"]').forEach(function (field) {
    field.lang = 'vi';
});

document.querySelectorAll('textarea[data-rich-editor]').forEach(createRichEditor);

function createRichEditor(textarea) {
    const shell = document.createElement('div');
    const toolbar = document.createElement('div');
    const surface = document.createElement('div');
    const status = document.createElement('div');
    const picker = document.createElement('input');
    let savedRange = null;
    let activeMedia = null;

    shell.className = 'rich-editor';
    toolbar.className = 'rich-editor-toolbar';
    toolbar.setAttribute('role', 'toolbar');
    surface.className = 'rich-editor-surface';
    surface.lang = textarea.lang || 'en';
    surface.contentEditable = 'true';
    surface.setAttribute('role', 'textbox');
    surface.setAttribute('aria-multiline', 'true');
    surface.setAttribute('aria-label', textarea.dataset.richEditor || 'Rich text');
    status.className = 'rich-editor-status';
    status.setAttribute('aria-live', 'polite');
    picker.type = 'file';
    picker.multiple = true;
    picker.hidden = true;

    const initial = textarea.value.trim();
    surface.innerHTML = /<\s*[a-z][^>]*>/i.test(initial)
        ? sanitizeEditorHtml(initial)
        : initial.split(/\r?\n+/).filter(Boolean).map(function (line) {
            return '<p>' + escapeHtml(line) + '</p>';
        }).join('');

    addTextButtons(toolbar, surface, sync);
    toolbar.appendChild(button('Insert Image', function () {
        savedRange = currentRange(surface) || savedRange;
        picker.accept = 'image/*';
        picker.click();
    }));
    toolbar.appendChild(button('Insert Video', function () {
        savedRange = currentRange(surface) || savedRange;
        picker.accept = 'video/*';
        picker.click();
    }));
    toolbar.appendChild(button('Media Library', async function () {
        savedRange = currentRange(surface) || savedRange;
        await openLibrary(function (item) {
            savedRange = insertMedia(surface, item, savedRange);
            sync();
        });
    }));

    const inspector = createInspector();
    inspector.onChange = function (settings) {
        if (!activeMedia) return;
        activeMedia.dataset.align = 'center';
        activeMedia.dataset.width = settings.width;
        activeMedia.style.width = settings.width + '%';
        const image = activeMedia.querySelector('img');
        const caption = activeMedia.querySelector('figcaption');
        if (image) image.alt = settings.alt;
        caption.textContent = settings.caption;
        caption.hidden = settings.caption === '';
        sync();
    };
    inspector.onRemove = function () {
        if (!activeMedia) return;
        activeMedia.remove();
        activeMedia = null;
        inspector.hidden = true;
        status.textContent = 'Removed from content. The file remains in Media Library.';
        sync();
    };

    picker.addEventListener('change', async function () {
        await uploadFiles(Array.from(picker.files || []), savedRange);
        picker.value = '';
    });

    ['mouseup', 'keyup', 'focus'].forEach(function (eventName) {
        surface.addEventListener(eventName, function () {
            savedRange = currentRange(surface) || savedRange;
        });
    });
    surface.addEventListener('input', sync);
    surface.addEventListener('click', function (event) {
        const block = event.target.closest('.editor-media-block');
        surface.querySelectorAll('.editor-media-block.is-selected').forEach(function (item) {
            item.classList.remove('is-selected');
        });
        if (!block || !surface.contains(block)) {
            activeMedia = null;
            inspector.hidden = true;
            return;
        }
        event.preventDefault();
        activeMedia = block;
        activeMedia.classList.add('is-selected');
        inspector.hidden = false;
        inspector.update(activeMedia);
    });
    surface.addEventListener('dragover', function (event) {
        if (hasMedia(event.dataTransfer)) {
            event.preventDefault();
            surface.classList.add('is-dragging-media');
        }
    });
    surface.addEventListener('dragleave', function () {
        surface.classList.remove('is-dragging-media');
    });
    surface.addEventListener('drop', async function (event) {
        if (!hasMedia(event.dataTransfer)) return;
        event.preventDefault();
        surface.classList.remove('is-dragging-media');
        const files = Array.from(event.dataTransfer.files).filter(function (file) {
            return file.type.startsWith('image/') || file.type.startsWith('video/');
        });
        await uploadFiles(files, rangeAtPoint(event.clientX, event.clientY, surface));
    });

    textarea.form?.addEventListener('submit', sync);
    textarea.hidden = true;
    shell.append(toolbar, surface, inspector, status, picker);
    textarea.insertAdjacentElement('afterend', shell);

    function sync() {
        textarea.value = surface.innerHTML;
    }

    async function uploadFiles(files, range) {
        if (!files.length) return;
        shell.classList.add('is-uploading');
        status.classList.remove('is-error');
        status.textContent = 'Uploading ' + files.length + ' file(s)...';
        let nextRange = range;
        try {
            for (const file of files) {
                const item = await upload(file);
                nextRange = insertMedia(surface, item, nextRange);
            }
            savedRange = nextRange;
            status.textContent = 'Upload complete.';
            sync();
        } catch (error) {
            status.textContent = error.message || 'Upload failed.';
            status.classList.add('is-error');
        } finally {
            shell.classList.remove('is-uploading');
        }
    }
}

function addTextButtons(toolbar, surface, sync) {
    [
        ['P', 'formatBlock', 'p'], ['H2', 'formatBlock', 'h2'],
        ['H3', 'formatBlock', 'h3'], ['B', 'bold'], ['I', 'italic'],
        ['U', 'underline'], ['Bullet list', 'insertUnorderedList'],
        ['1. List', 'insertOrderedList'], ['Quote', 'formatBlock', 'blockquote']
    ].forEach(function (action) {
        toolbar.appendChild(button(action[0], function () {
            surface.focus();
            document.execCommand(action[1], false, action[2] || null);
            sync();
        }, true));
    });
    toolbar.appendChild(button('Link', function () {
        const address = window.prompt('Enter an https:// URL or a website path beginning with /');
        if (address && /^(https?:\/\/|\/(?!\/))/i.test(address.trim())) {
            surface.focus();
            document.execCommand('createLink', false, address.trim());
            sync();
        }
    }, true));
    toolbar.appendChild(button('Table 2x2', function () {
        surface.focus();
        document.execCommand('insertHTML', false, '<table><tbody><tr><th>Heading</th><th>Heading</th></tr><tr><td>Cell</td><td>Cell</td></tr></tbody></table><p><br></p>');
        sync();
    }, true));
}

function button(label, handler, preserveSelection) {
    const element = document.createElement('button');
    element.type = 'button';
    element.textContent = label;
    if (preserveSelection) element.addEventListener('mousedown', function (event) { event.preventDefault(); });
    element.addEventListener('click', handler);
    return element;
}

function createInspector() {
    const inspector = document.createElement('div');
    inspector.className = 'rich-editor-inspector';
    inspector.hidden = true;
    inspector.innerHTML =
        '<strong>Media settings</strong>' +
        '<label>Alt text<input type="text" data-setting="alt" placeholder="Describe the image"></label>' +
        '<label>Caption<input type="text" data-setting="caption" placeholder="Optional caption"></label>' +
        '<label class="rich-editor-width">Width <output data-width-output>100%</output><input type="range" min="10" max="100" step="1" value="100" data-setting="width"></label>' +
        '<button type="button" class="rich-editor-remove">Remove from content</button>';
    inspector.update = function (block) {
        inspector.querySelector('[data-setting="alt"]').value = block.querySelector('img')?.alt || '';
        inspector.querySelector('[data-setting="caption"]').value = block.querySelector('figcaption')?.textContent || '';
        const width = block.dataset.width || '100';
        inspector.querySelector('[data-setting="width"]').value = width;
        inspector.querySelector('[data-width-output]').textContent = width + '%';
    };
    function emit() {
        inspector.onChange?.({
            alt: inspector.querySelector('[data-setting="alt"]').value.trim(),
            caption: inspector.querySelector('[data-setting="caption"]').value.trim(),
            width: inspector.querySelector('[data-setting="width"]').value
        });
        inspector.querySelector('[data-width-output]').textContent = inspector.querySelector('[data-setting="width"]').value + '%';
    }
    inspector.querySelectorAll('input, select').forEach(function (field) {
        field.addEventListener('input', emit);
        field.addEventListener('change', emit);
    });
    inspector.querySelector('.rich-editor-remove').addEventListener('click', function () {
        inspector.onRemove?.();
    });
    return inspector;
}

async function upload(file) {
    if (!editorConfig.endpoint || !editorConfig.csrfToken) throw new Error('Editor upload configuration is missing.');
    const data = new FormData();
    data.append('action', 'upload');
    data.append('csrf_token', editorConfig.csrfToken);
    data.append('media_file', file);
    const response = await fetch(editorConfig.endpoint, { method: 'POST', body: data, credentials: 'same-origin' });
    const result = await response.json().catch(function () {
        return { ok: false, message: 'Invalid server response.' };
    });
    if (!response.ok || !result.ok) throw new Error(result.message || 'Upload failed.');
    return result.item;
}

async function openLibrary(onInsert) {
    document.querySelector('.rich-editor-library')?.remove();
    const dialog = document.createElement('dialog');
    dialog.className = 'rich-editor-library';
    dialog.innerHTML =
        '<div class="rich-editor-library-head"><div><strong>Media Library</strong><small>Select an item to insert it again.</small></div><button type="button" data-close aria-label="Close">x</button></div>' +
        '<div class="rich-editor-library-grid"><p>Loading...</p></div>';
    document.body.appendChild(dialog);
    dialog.querySelector('[data-close]').addEventListener('click', function () { dialog.close(); });
    dialog.addEventListener('close', function () { dialog.remove(); });
    dialog.showModal();
    try {
        const response = await fetch(editorConfig.endpoint + '?action=list', { credentials: 'same-origin' });
        const result = await response.json();
        const grid = dialog.querySelector('.rich-editor-library-grid');
        grid.innerHTML = '';
        if (!result.items?.length) {
            grid.innerHTML = '<p>No editor media has been uploaded yet.</p>';
            return;
        }
        result.items.forEach(function (item) {
            const mediaButton = document.createElement('button');
            mediaButton.type = 'button';
            mediaButton.className = 'rich-editor-library-item';
            const preview = item.media_type === 'video'
                ? '<video src="' + escapeHtml(item.url) + '" muted preload="metadata"></video>'
                : '<img src="' + escapeHtml(item.url) + '" alt="' + escapeHtml(item.alt_text || '') + '">';
            mediaButton.innerHTML = preview + '<span>' + escapeHtml(item.title || item.original_name || 'Untitled') + '</span>';
            mediaButton.addEventListener('click', function () {
                onInsert(item);
                dialog.close();
            });
            grid.appendChild(mediaButton);
        });
    } catch (error) {
        dialog.querySelector('.rich-editor-library-grid').innerHTML = '<p>Unable to load the media library.</p>';
    }
}

function insertMedia(surface, item, range) {
    const figure = document.createElement('figure');
    figure.className = 'editor-media-block';
    figure.dataset.align = 'center';
    figure.dataset.width = '100';
    figure.style.width = '100%';
    figure.contentEditable = 'false';
    if (item.media_type === 'video') {
        const video = document.createElement('video');
        video.src = item.url;
        video.controls = true;
        video.preload = 'metadata';
        figure.appendChild(video);
    } else {
        const image = document.createElement('img');
        image.src = item.url;
        image.alt = item.alt_text || item.title || '';
        figure.appendChild(image);
    }
    const caption = document.createElement('figcaption');
    caption.textContent = item.title || '';
    caption.hidden = !caption.textContent;
    figure.appendChild(caption);
    const paragraph = document.createElement('p');
    paragraph.innerHTML = '<br>';
    const validRange = range && surface.contains(range.commonAncestorContainer) ? range : null;
    if (validRange) {
        validRange.deleteContents();
        validRange.insertNode(paragraph);
        validRange.insertNode(figure);
    } else {
        surface.append(figure, paragraph);
    }
    const next = document.createRange();
    next.setStart(paragraph, 0);
    next.collapse(true);
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(next);
    return next.cloneRange();
}

function currentRange(surface) {
    const selection = window.getSelection();
    if (!selection?.rangeCount) return null;
    const range = selection.getRangeAt(0);
    return surface.contains(range.commonAncestorContainer) ? range.cloneRange() : null;
}

function rangeAtPoint(x, y, surface) {
    let range = document.caretRangeFromPoint?.(x, y) || null;
    if (!range && document.caretPositionFromPoint) {
        const position = document.caretPositionFromPoint(x, y);
        if (position) {
            range = document.createRange();
            range.setStart(position.offsetNode, position.offset);
            range.collapse(true);
        }
    }
    return range && surface.contains(range.commonAncestorContainer) ? range : null;
}

function hasMedia(dataTransfer) {
    return Array.from(dataTransfer?.files || []).some(function (file) {
        return file.type.startsWith('image/') || file.type.startsWith('video/');
    });
}

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, function (character) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[character];
    });
}

function sanitizeEditorHtml(html) {
    const source = document.createElement('template');
    source.innerHTML = html;
    const allowed = new Set(['P', 'BR', 'H2', 'H3', 'H4', 'STRONG', 'B', 'EM', 'I', 'U', 'UL', 'OL', 'LI', 'BLOCKQUOTE', 'TABLE', 'THEAD', 'TBODY', 'TR', 'TH', 'TD', 'A', 'FIGURE', 'FIGCAPTION', 'IMG', 'VIDEO', 'SOURCE']);
    const dropped = new Set(['SCRIPT', 'STYLE', 'IFRAME', 'OBJECT', 'SVG', 'FORM']);
    function clean(node) {
        if (node.nodeType === Node.TEXT_NODE) return document.createTextNode(node.textContent);
        if (node.nodeType !== Node.ELEMENT_NODE || dropped.has(node.tagName)) return document.createDocumentFragment();
        const output = allowed.has(node.tagName) ? document.createElement(node.tagName.toLowerCase()) : document.createDocumentFragment();
        if (node.tagName === 'A') copyUrl(node, output, 'href');
        if (node.tagName === 'IMG') {
            copyUrl(node, output, 'src');
            output.alt = node.getAttribute('alt') || '';
        }
        if (node.tagName === 'VIDEO') {
            copyUrl(node, output, 'src');
            output.controls = true;
            output.preload = 'metadata';
        }
        if (node.tagName === 'SOURCE') copyUrl(node, output, 'src');
        if (node.tagName === 'FIGURE') {
            const width = Math.max(10, Math.min(100, Number(node.dataset.width) || 100));
            output.className = 'editor-media-block';
            output.dataset.align = 'center';
            output.dataset.width = String(width);
            output.style.width = width + '%';
            output.contentEditable = 'false';
        }
        node.childNodes.forEach(function (child) { output.appendChild(clean(child)); });
        return output;
    }
    const result = document.createElement('div');
    source.content.childNodes.forEach(function (node) { result.appendChild(clean(node)); });
    return result.innerHTML;
}

function copyUrl(source, target, attribute) {
    const value = source.getAttribute(attribute) || '';
    if (/^(https?:\/\/|\/(?!\/))/i.test(value)) target.setAttribute(attribute, value);
}
