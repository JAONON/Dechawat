<style>
    .sidebar {
        width: 100%;
        min-height: 100vh;
        background-color: #f4f4f4;
        padding: 15px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar ul {
        list-style: none;
        padding: 0;
    }
    .sidebar ul li {
        margin: 10px 0;
    }
    .sidebar ul li a {
        text-decoration: none;
        color: #333;
        display: block;
        padding: 8px;
        border-radius: 4px;
    }
    .sidebar ul li a:hover {
        background-color: #ddd;
    }
    .sidebar ul li ul {
        margin-left: 15px;
    }
</style>

<?php
    $menuItems["employee"] = [
        [
            'title' => 'Home',
            'link' => '../project/getproject.php',
            'submenu' => []
        ],
        // [
        //     'title' => 'Project',
        //     'link' => '#',
        //     'submenu' => [
        //         ['title' => 'List', 'link' => '../project/getproject.php'],
        //         ['title' => 'Add', 'link' => '../project/addproject.php']
        //     ]
        // ],
        [
            'title' => 'Menu 3',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Menu 4',
            'link' => '#',
            'submenu' => [
                ['title' => 'Submenu 4.1', 'link' => '#'],
                ['title' => 'Submenu 4.2', 'link' => '#']
            ]
        ],
        [
            'title' => 'Menu 5',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Menu 6',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Menu 8',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Profile',
            'link' => '../profile/get_profile.php',
            'submenu' => []
        ],
        [
            'title' => 'Logout',
            'link' => '../../logout/logout.php',
            'submenu' => []
        ]
    ];
    $menuItems["employer"] = [
        [
            'title' => 'Home',
            'link' => '../index/index.php',
            'submenu' => []
        ],
        [
            'title' => 'Project',
            'link' => '#',
            'submenu' => [
                ['title' => 'List', 'link' => '../project/getproject.php'],
                ['title' => 'Add', 'link' => '../project/addproject.php']
            ]
        ],
        [
            'title' => 'Menu 3',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Menu 4',
            'link' => '#',
            'submenu' => [
                ['title' => 'Submenu 4.1', 'link' => '#'],
                ['title' => 'Submenu 4.2', 'link' => '#']
            ]
        ],
        [
            'title' => 'Menu 5',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Menu 6',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Menu 8',
            'link' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Profile',
            'link' => '../profile/get_profile.php',
            'submenu' => []
        ],
        [
            'title' => 'Logout',
            'link' => '../logout/logout.php',
            'submenu' => []
        ]
    ];
?>
<div class="sidebar">
    <ul>
        <?php foreach ($menuItems[$_SESSION["member"]["role"]] as $index => $menuItem){ ?>
            <li>
                <a href="<?= $menuItem['link']; ?>" 
                   <?= !empty($menuItem['submenu']) ? "onclick=\"toggleSubmenu(event, 'submenu-$index')\"" : ""; ?>>
                    <?= $menuItem['title']; ?>
                    <?php if (!empty($menuItem['submenu'])){ ?>
                        <span id="arrow-submenu-<?= $index; ?>">▼</span>
                    <?php }; ?>
                </a>
                <?php if (!empty($menuItem['submenu'])){ ?>
                    <ul id="submenu-<?= $index; ?>" style="display: none;">
                        <?php foreach ($menuItem['submenu'] as $submenuItem){ ?>
                            <li><a href="<?= $submenuItem['link']; ?>"><?= $submenuItem['title']; ?></a></li>
                        <?php }; ?>
                    </ul>
                <?php }; ?>
            </li>
        <?php }; ?>
    </ul>
</div>
<script>
    function toggleSubmenu(event, submenuId) {
        event.preventDefault();
        const submenu = document.getElementById(submenuId);
        const arrow = document.getElementById('arrow-' + submenuId);
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
            arrow.textContent = '▲';
        } else {
            submenu.style.display = 'none';
            arrow.textContent = '▼';
        }
    }
</script>