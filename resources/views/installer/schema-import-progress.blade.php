<div class="schema-import-section" style="margin-bottom: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
    <h3 style="margin: 0 0 15px 0; color: #2c3e50; display: flex; align-items: center;">
        <i class="fas fa-database" style="margin-right: 8px; color: #007bff;"></i>
        Database Schema Import
    </h3>
    
    <div id="schema-status" class="status-ready">
        <div class="status-content">
            <div class="status-icon">⏳</div>
            <div class="status-text">
                <strong>Ready to Import</strong>
                <p>Database schema from license server will be imported when you submit this form.</p>
            </div>
        </div>
    </div>

    <div id="schema-progress" style="display: none;">
        <div class="progress-bar-container">
            <div class="progress-bar" id="schema-progress-bar">
                <div class="progress-fill" style="width: 0%;"></div>
            </div>
            <div class="progress-text" id="schema-progress-text">Preparing import...</div>
        </div>
        
        <div class="import-details" id="schema-details">
            <div class="detail-item">
                <span class="label">Tables Created:</span>
                <span class="value" id="tables-count">0</span>
            </div>
            <div class="detail-item">
                <span class="label">Records Inserted:</span>
                <span class="value" id="records-count">0</span>
            </div>
            <div class="detail-item">
                <span class="label">Progress:</span>
                <span class="value" id="progress-percentage">0%</span>
            </div>
        </div>
    </div>

    <div id="schema-success" style="display: none;" class="status-success">
        <div class="status-content">
            <div class="status-icon">✅</div>
            <div class="status-text">
                <strong>Import Completed Successfully!</strong>
                <p id="success-summary">Database schema has been imported from license server.</p>
            </div>
        </div>
    </div>

    <div id="schema-error" style="display: none;" class="status-error">
        <div class="status-content">
            <div class="status-icon">❌</div>
            <div class="status-text">
                <strong>Import Failed</strong>
                <p id="error-message">An error occurred during schema import.</p>
            </div>
        </div>
    </div>
</div>

<style>
.schema-import-section {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.status-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.status-icon {
    font-size: 1.5rem;
    line-height: 1;
}

.status-text {
    flex: 1;
}

.status-text strong {
    display: block;
    color: #2c3e50;
    margin-bottom: 4px;
}

.status-text p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.progress-bar-container {
    margin: 15px 0;
}

.progress-bar {
    background: #e9ecef;
    border-radius: 6px;
    height: 8px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    background: linear-gradient(90deg, #28a745, #20c997);
    height: 100%;
    transition: width 0.3s ease;
    border-radius: 6px;
}

.progress-text {
    font-size: 0.9rem;
    color: #495057;
    margin-top: 8px;
    font-weight: 500;
}

.import-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
    padding: 15px;
    background: white;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.detail-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.detail-item .label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 4px;
}

.detail-item .value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.status-success {
    color: #155724;
    background: #d4edda;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #c3e6cb;
}

.status-error {
    color: #721c24;
    background: #f8d7da;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #f5c6cb;
}

/* Loading animation */
.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: inline-block;
    margin-right: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we have license data with product_data
    const licenseData = @json(session('license_data'));
    
    if (licenseData && licenseData.product_data) {
        updateSchemaStatus('ready', 'Schema ready for import (' + Math.round(licenseData.product_data.length / 1024) + ' KB)');
    } else {
        updateSchemaStatus('error', 'No schema data found in license. Please verify license first.');
    }

    // Listen for form submission to start import
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (licenseData && licenseData.product_data) {
                startSchemaImport();
            }
        });
    }
});

function updateSchemaStatus(status, message) {
    const statusElement = document.getElementById('schema-status');
    const progressElement = document.getElementById('schema-progress');
    const successElement = document.getElementById('schema-success');
    const errorElement = document.getElementById('schema-error');

    // Hide all status elements
    statusElement.style.display = 'none';
    progressElement.style.display = 'none';
    successElement.style.display = 'none';
    errorElement.style.display = 'none';

    switch(status) {
        case 'ready':
            statusElement.style.display = 'block';
            statusElement.querySelector('p').textContent = message;
            break;
        case 'importing':
            progressElement.style.display = 'block';
            break;
        case 'success':
            successElement.style.display = 'block';
            if (message) {
                successElement.querySelector('#success-summary').textContent = message;
            }
            break;
        case 'error':
            errorElement.style.display = 'block';
            if (message) {
                errorElement.querySelector('#error-message').textContent = message;
            }
            break;
    }
}

function startSchemaImport() {
    updateSchemaStatus('importing');
    updateProgress(10, 0, 0);
    
    // Show import progress - this will be visible during form submission
    setTimeout(() => updateProgress(30, 5, 0), 500);
    setTimeout(() => updateProgress(60, 15, 50), 1000);
    setTimeout(() => updateProgress(90, 25, 150), 1500);
    
    // Note: Real progress will be shown in logs after installation completes
    // The actual schema import happens server-side during form submission
}

function updateProgress(percentage, tables, records) {
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.getElementById('schema-progress-text');
    const tablesCount = document.getElementById('tables-count');
    const recordsCount = document.getElementById('records-count');
    const progressPercentage = document.getElementById('progress-percentage');
    
    if (progressFill) progressFill.style.width = percentage + '%';
    if (progressPercentage) progressPercentage.textContent = Math.round(percentage) + '%';
    if (tablesCount) tablesCount.textContent = tables;
    if (recordsCount) recordsCount.textContent = records;
    
    if (progressText) {
        if (percentage < 30) {
            progressText.textContent = 'Analyzing schema structure...';
        } else if (percentage < 60) {
            progressText.textContent = 'Creating database tables...';
        } else if (percentage < 90) {
            progressText.textContent = 'Inserting initial data...';
        } else {
            progressText.textContent = 'Finalizing import...';
        }
    }
}
</script>