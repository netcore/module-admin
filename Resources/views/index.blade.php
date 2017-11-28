@extends('admin::layouts.master')

@section('styles')
    <style>
        .Dashboard {
            border: 0px solid #d4d2d0;
            background: #fff;
            border-radius: 3px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .analytics-container {
            padding: 25px;
            padding-top: 15px;
        }

        #analytics-dashboard {
            display: none;
        }

        .Dashboard-header {
            border-bottom: 0px solid #d4d2d0;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
        }

        .Dashboard-footer, .Dashboard-header {
            margin: -1.5em -1.5em 1.5em;
            padding: 1.5em;
        }

        .Dashboard-footer {
            border-top: 1px solid #d4d2d0;
        }

        .Dashboard--full {
            max-width: 100%;
        }

        .Dashboard--collapseBottom {
            padding-bottom: 0;
        }

        @media (min-width: 1024px) {
            .Dashboard, .Dashboard-header {
                padding: 2em;
            }

            .Dashboard-footer, .Dashboard-header {
                margin: -2em -2em 2em;
            }

            .Dashboard-footer {
                padding: 1.5em;
            }

            .Dashboard--collapseBottom {
                padding-bottom: 0.5em;
            }
        }
        
        #view-selector-container {
            display: flex;
        }
    </style>
@endsection

@section('content')
    {!! Breadcrumbs::render('admin') !!}

    @if(view()->exists('admin.dashboard'))
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation">
                    <a href="#tab-analytics" aria-controls="tab-analytics" role="tab" data-toggle="tab">Google Analytics</a>
                </li>

                <li role="presentation" class="active">
                    <a href="#tab-dashboard" aria-controls="tab-dashboard" role="tab" data-toggle="tab">Site dashboard</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade" id="tab-analytics">
                    @include('admin::_partials._analytics')
                </div>
                <div role="tabpanel" class="tab-pane fade in active" id="tab-dashboard">
                    @include('admin.dashboard')
                </div>
            </div>

        </div>
    @else
        @include('admin::_partials._analytics')
    @endif
@endsection

@section('scripts')
    @if ($google_analytics_client_id)
    <script>
        (function (w, d, s, g, js, fs) {
            g = w.gapi || (w.gapi = {});
            g.analytics = {
                q: [], ready: function (f) {
                    this.q.push(f);
                }
            };
            js = d.createElement(s);
            fs = d.getElementsByTagName(s)[0];
            js.src = 'https://apis.google.com/js/platform.js';
            fs.parentNode.insertBefore(js, fs);
            js.onload = function () {
                g.load('analytics');
            };
        }(window, document, 'script'));

        gapi.analytics.ready(function () {
            /**
             * Authorize the user immediately if the user has already granted access.
             * If no access has been created, render an authorize button inside the
             * element with the ID "embed-api-auth-container".
             */
            gapi.analytics.auth.authorize({
                container: 'embed-api-auth-container',
                clientid: '{{ $google_analytics_client_id }}'
            });

            /**
             * Create a new ViewSelector instance to be rendered inside of an
             * element with the id "view-selector-container".
             */
            var viewSelector = new gapi.analytics.ViewSelector({
                container: 'view-selector-container'
            });

            // Render the view selector to the page.
            viewSelector.execute();

            /**
             * Create a new DataChart instance with the given query parameters
             * and Google chart options. It will be rendered inside an element
             * with the id "chart-container".
             */
            var dataChart = new gapi.analytics.googleCharts.DataChart({
                query: {
                    metrics: 'ga:sessions',
                    dimensions: 'ga:date',
                    'start-date': '30daysAgo',
                    'end-date': 'today'
                },
                chart: {
                    container: 'chart-container',
                    type: 'LINE',
                    options: {
                        width: '100%'
                    }
                }
            });

            /**
             * Render the dataChart on the page whenever a new view is selected.
             */
            viewSelector.on('change', function (ids) {
                dataChart.set({query: {ids: ids}}).execute();
            });

        });
    </script>
    @endif
@endsection
