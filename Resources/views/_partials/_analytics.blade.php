@if (isset($google_analytics_client_id) && !empty($google_analytics_client_id))
    <div class="Dashboard">
        <div id="embed-api-auth-container"></div>
        <div id="chart-container"></div>
        <div id="view-selector-container"></div>
    </div>
@else
    <p style="border-radius:4px; padding:20px; background:#fff; margin:0; color:#999; text-align:center;">
        Google Analytics Client ID is not set.<br/>
        Get it from
        <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>
        and set it in Settings.
    </p>
@endif