<div class="mc-menu-search">
    <i class="fas fa-search" aria-hidden="true"></i>
    <input type="search" id="mcMenuSearch" autocomplete="off" placeholder="<?php echo lang('search'); ?>" aria-label="<?php echo lang('search'); ?>">
</div>
<div class="mc-menu-empty" id="mcMenuEmpty" role="status" hidden><?php echo lang('menu_no_results'); ?></div>
<script>
    // Filters the sidebar as the user types. Enter opens the first match.
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('mcMenuSearch');
        var empty = document.getElementById('mcMenuEmpty');
        var menu = document.querySelector('.mc-sidebar .nav-sidebar');
        if (!input || !menu) { return; }

        function normalize(text) {
            return text.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        }

        function label(item) {
            var link = item.querySelector(':scope > .nav-link');
            return link ? normalize(link.textContent) : '';
        }

        function setVisible(item, visible) {
            item.style.display = visible ? '' : 'none';
        }

        function reset() {
            menu.querySelectorAll('li').forEach(function (item) { item.style.display = ''; });
            menu.querySelectorAll('.nav-treeview').forEach(function (list) {
                list.style.display = list.dataset.mcDisplay || '';
                delete list.dataset.mcDisplay;
            });
            empty.hidden = true;
        }

        function filter(query) {
            var found = 0;
            menu.querySelectorAll(':scope > .nav-item').forEach(function (item) {
                var list = item.querySelector(':scope > .nav-treeview');
                var own = label(item).indexOf(query) !== -1;
                var children = list ? list.querySelectorAll(':scope > .nav-item') : [];
                var childMatch = false;

                children.forEach(function (child) {
                    var match = own || label(child).indexOf(query) !== -1;
                    setVisible(child, match);
                    childMatch = childMatch || match;
                });

                if (list) {
                    if (!('mcDisplay' in list.dataset)) { list.dataset.mcDisplay = list.style.display; }
                    list.style.display = childMatch ? 'block' : 'none';
                }

                var visible = own || childMatch;
                setVisible(item, visible);
                found += visible ? 1 : 0;
            });

            menu.querySelectorAll(':scope > .nav-header').forEach(function (header) {
                var next = header.nextElementSibling;
                var hasItems = false;
                while (next && !next.classList.contains('nav-header')) {
                    hasItems = hasItems || next.style.display !== 'none';
                    next = next.nextElementSibling;
                }
                setVisible(header, hasItems);
            });

            empty.hidden = found > 0;
        }

        input.addEventListener('input', function () {
            var query = normalize(input.value);
            if (query) { filter(query); } else { reset(); }
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                input.value = '';
                reset();
            }
            if (event.key === 'Enter') {
                event.preventDefault();
                var links = Array.prototype.filter.call(menu.querySelectorAll('a.nav-link[href]'), function (link) {
                    return link.getAttribute('href') !== '#' && link.offsetParent !== null;
                });
                if (links.length) { links[0].click(); }
            }
        });
    });
</script>
