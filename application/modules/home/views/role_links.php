<?php $role_links = role_link_rules(); ?>
<?php if ($role_links !== null) { ?>
<script>
    // Removes links, buttons and module choices the current role must not see, including rows loaded later.
    (function () {
        var rules = <?php echo json_encode($role_links); ?>;
        var base = new URL(document.baseURI).pathname.toLowerCase();

        var currentClass = location.pathname.toLowerCase().slice(base.length).split('/')[0];
        if (rules.readonly.indexOf(currentClass) !== -1) {
            var style = document.createElement('style');
            style.textContent = 'a[href="#myModal"].btn-primary, a.editbutton:not([href]) { display: none !important; }';
            document.head.appendChild(style);
        }

        function isHidden(link) {
            var url;
            try { url = new URL(link.href, document.baseURI); } catch (e) { return false; }
            if (url.origin !== location.origin) { return false; }
            var path = url.pathname.toLowerCase();
            if (path.indexOf(base) !== 0) { return false; }
            var parts = path.slice(base.length).split('/').filter(Boolean);
            if (!parts.length || parts[0] === 'index.php') { return false; }
            var page = parts[0] + '/' + (parts[1] || 'index');
            if (rules.blocked.indexOf(page) !== -1) { return true; }
            return rules.hidden.indexOf(parts[0]) !== -1 && rules.pages.indexOf(page) === -1;
        }

        function clean(root) {
            if (!root.querySelectorAll) { return; }
            root.querySelectorAll('a[href]').forEach(function (link) {
                if ((link.getAttribute('href') || '').charAt(0) !== '#' && isHidden(link)) {
                    var item = link.closest('li');
                    (item && item.querySelectorAll('a').length === 1 ? item : link).remove();
                }
            });
            // Hide forms the role may not submit, and the buttons that open them.
            root.querySelectorAll('form[action]').forEach(function (form) {
                var probe = document.createElement('a');
                probe.href = form.getAttribute('action');
                if (!isHidden(probe)) { return; }
                var modal = form.closest('.modal');
                if (modal && modal.id) {
                    document.querySelectorAll('[href="#' + modal.id + '"], [data-target="#' + modal.id + '"]').forEach(function (button) {
                        button.style.display = 'none';
                    });
                } else {
                    form.style.display = 'none';
                }
            });
            rules.hiddenModules.forEach(function (name) {
                root.querySelectorAll('input[name="module[]"][value="' + name + '"]').forEach(function (box) {
                    box.checked = false;
                    (box.closest('[class*="col-"], .form-check, label') || box).remove();
                });
            });
        }

        function start() {
            clean(document.body);
            new MutationObserver(function (changes) {
                changes.forEach(function (change) { change.addedNodes.forEach(clean); });
            }).observe(document.body, { childList: true, subtree: true });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', start);
        } else {
            start();
        }
    })();
</script>
<?php } ?>
