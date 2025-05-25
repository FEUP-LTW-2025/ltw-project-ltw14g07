<?php function draw_admin_controls($data = []) { ?>
    <div class="admin-container">
        <!-- Message Display -->
        <?php if (!empty($data['messages']['success'])): ?>
            <div class="alert success"><?= htmlspecialchars($data['messages']['success']) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($data['messages']['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($data['messages']['error']) ?></div>
        <?php endif; ?>

        <h1>System Administration</h1>
        
        <!-- 1. User Management Section (unchanged) -->
        <section class="admin-section">
            <h2>User Management</h2>
            
                        <form method="POST" class="admin-form">
                <input type="hidden" name="action" value="promote_user">
                
                <div class="form-group">
                    <label for="user_email">User Email:</label>
                    <input type="email" id="user_email" name="user_email" required>
        </div>
                
        <button type="submit" class="btn-demote">Elevate to Admin</button>

            </form>
            
            <div class="current-admins">
                <h3>Current Administrators</h3>
                <ul>
                    <?php foreach ($data['admins'] as $admin): ?>
                    <li>
                        <?= htmlspecialchars($admin->name) ?> (<?= htmlspecialchars($admin->email) ?>)
                        <?php if ($admin->userID !== $_SESSION['userID']): ?>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="action" value="demote_user">
                                <input type="hidden" name="demoteUserID" value="<?= $admin->userID ?>">
                                <button type="submit" class="btn-demote" 
                                        onclick="return confirm('Demote <?= htmlspecialchars($admin->name) ?>?')">
                                    Demote
                                </button>
                            </form>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <!-- 2. Combined Service Management Section -->
        <section class="admin-section">
            <h2>Service Management</h2>
            
            <!-- Categories Subsection -->
            <div class="subsection">
                <h3 class="subsection-header" onclick="toggleSubsection(this)">
                    <span class="toggle-icon">-</span> Categories
                </h3>
                <div class="subsection-content">
                    <form method="POST" class="admin-form">
                        <input type="hidden" name="action" value="add_category">
                        <div class="form-group">
                            <label for="category_name">Category Name:</label>
                            <input type="text" id="category_name" name="category_name" required>
                            <label for="category_description">Description:</label>
                            <textarea id="category_description" name="category_description"></textarea>
                            <button type="submit" class="btn-demote">Add Category</button>

                        </div>
                    </form>
                    
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['categories'] as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['Name']) ?></td>
                                <td><?= htmlspecialchars($category['Description']) ?></td>
                                <td>
                                    <button class="btn-edit" 
                                            onclick="openEditModal(<?= $category['CategoryID'] ?>, 
                                            '<?= htmlspecialchars($category['Name']) ?>', 
                                            '<?= htmlspecialchars($category['Description']) ?>')">
                                        Edit
                                    </button>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="action" value="delete_category">
                                        <input type="hidden" name="category_id" value="<?= $category['CategoryID'] ?>">
                                        <button type="submit" class="btn-delete"
                                                onclick="return confirm('Delete this category?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Languages Subsection -->
            <div class="subsection">
                <h3 class="subsection-header" onclick="toggleSubsection(this)">
                    <span class="toggle-icon">+</span> Languages
                </h3>
                <div class="subsection-content" style="display:none;">
                    <form method="POST" class="admin-form">
                        <input type="hidden" name="action" value="add_language">
                        <div class="form-group">
                            <input type="text" name="language_name" placeholder="New language (e.g. Python)" required>
                            <button type="submit" class="btn-demote">Add</button>
                           
                        </div>
                    </form>
                    
                    <table class="admin-table">
                        <thead>
                            <tr><th>Name</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['languages'] as $lang): ?>
                            <tr>
                                <td><?= htmlspecialchars($lang['language']) ?></td>
                                <td>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="action" value="delete_language">
                                        <input type="hidden" name="name" value="<?= htmlspecialchars($lang['language']) ?>">
                                        <button type="submit" class="btn-delete" 
                                                onclick="return confirm('Delete <?= htmlspecialchars($lang['language']) ?>?')">
                                            Delete
                                        </button>

                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Fields Subsection -->
            <div class="subsection">
                <h3 class="subsection-header" onclick="toggleSubsection(this)">
                    <span class="toggle-icon">+</span> Fields
                </h3>
                <div class="subsection-content" style="display:none;">
                    <form method="POST" class="admin-form">
                        <input type="hidden" name="action" value="add_field">
                        <div class="form-group">
                            <input type="text" name="field_name" placeholder="New field (e.g. Web Development)" required>
                            <button type="submit" class="btn-demote">Add</button>
                        </div>
                    </form>
                    
                    <table class="admin-table">
                        <thead>
                            <tr><th>Name</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['fields'] as $field): ?>
                            <tr>
                                <td><?= htmlspecialchars($field['field']) ?></td>
                                <td>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="action" value="delete_field">
                                        <input type="hidden" name="name" value="<?= htmlspecialchars($field['field']) ?>">
                                        <button type="submit" class="btn-delete" 
                                                onclick="return confirm('Delete <?= htmlspecialchars($field['field']) ?>?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Edit Modal -->
            <div id="editModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h3>Edit Category</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_category">
                        <input type="hidden" id="edit_category_id" name="category_id">
                        
                        <div class="form-group">
                            <label for="edit_category_name">Name:</label>
                            <input type="text" id="edit_category_name" name="category_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_category_description">Description:</label>
                            <textarea id="edit_category_description" name="category_description"></textarea>
                        </div>
                        
                        <button type="submit">Save Changes</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- 3. System Monitoring Section (unchanged) -->
        <section class="admin-section">
            <h2>System Monitoring</h2>
            <div class="system-stats">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><?= $data['stats']['total_users'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Active Services</h3>
                    <p><?= $data['stats']['active_services'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Categories</h3>
                    <p><?= $data['stats']['total_categories'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Languages</h3>
                    <p><?= $data['stats']['total_languages'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Fields</h3>
                    <p><?= $data['stats']['total_fields'] ?></p>
                </div>
            </div>
            
            <div class="recent-logs">
                <h3>Recent System Activities</h3>
                <ul>
                    <?php foreach ($data['recent_logs'] as $log): ?>
                    <li>
                        <span class="log-time">[<?= $log['Timestamp'] ?>]</span>
                        <span class="log-user"><?= htmlspecialchars($log['user_name']) ?>:</span>
                        <span class="log-action"><?= htmlspecialchars($log['Action']) ?></span>
                        <?php if (!empty($log['Details'])): ?>
                        <span class="log-details">(<?= htmlspecialchars($log['Details']) ?>)</span>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="/pages/admin_logs.php" class="view-all">View All Logs</a>
            </div>
        </section>
        
        <script>
            // Subsection toggle functionality
            function toggleSubsection(header) {
                const content = header.nextElementSibling;
                const icon = header.querySelector('.toggle-icon');
                
                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    icon.textContent = '-';
                } else {
                    content.style.display = 'none';
                    icon.textContent = '+';
                }
            }
            
            // Modal functions
            function openEditModal(id, name, description) {
                document.getElementById('edit_category_id').value = id;
                document.getElementById('edit_category_name').value = name;
                document.getElementById('edit_category_description').value = description;
                document.getElementById('editModal').style.display = 'block';
            }

            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }
            
            window.onclick = function(event) {
                const modal = document.getElementById('editModal');
                if (event.target === modal) {
                    closeEditModal();
                }
            };
        </script>
        <style>
        .subsection {
            margin-bottom: 1.5rem;
            border: 1px solid #d1d5db; /* light gray-blue */
            border-radius: 8px;
            padding: 1rem 1.5rem;
            background-color: #fafafa;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        .subsection-header {
            cursor: pointer;
            padding: 0.75rem 0;
            margin: 0;
            display: flex;
            align-items: center;
            font-size: 1.15rem;
            font-weight: 600;
            color: #2c3e50; /* dark slate */
            user-select: none;
            transition: color 0.3s ease;
        }
        .subsection-header:hover {
            color: #1abc9c; /* teal accent on hover */
        }

        .toggle-icon {
            display: inline-block;
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        /* Add a class "open" to subsection-header when expanded */
        .subsection-header.open .toggle-icon {
            transform: rotate(90deg);
            color: #1abc9c;
        }

        .subsection-content {
            padding: 0.75rem 0 1.25rem;
            border-top: 1px solid #e2e8f0; /* subtle border */
            margin-top: 0.75rem;
            color: #444;
            font-size: 1rem;
            line-height: 1.5;
            transition: max-height 0.35s ease, opacity 0.3s ease;
            overflow: hidden;
        }
        </style>

    </div>
<?php } ?>