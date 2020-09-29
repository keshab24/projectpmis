<link href="{{ asset('public/admin/css/proStyleAdmin.css') }}" rel="stylesheet" type="text/css">
<div class="container-fluid" data-appear-animation="fadeInLeft" data-appear-delay="400">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2>Procurement Value Missing </h2>
                    @if(isset($implementing_office_name))
                        <h3>
                            {{ $implementing_office_name }}
                        </h3>
                    @endif
                </div>
                <div class="pro_content" data-appear-animation="fadeInUp" data-appear-delay="1000">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th class="text-center">
                                कोड
                            </th>
                            <th class="text-center">
                                आयोजनाको नाम
                            </th>
                            <th class="text-center">
                                Missing Attributes
                            </th>
                        </tr>
                        @foreach($projects as $project)
                            <tr>
                                <td>
                                    {{ $project->project_code }}
                                </td>
                                <td>
                                    {{ $project->name }}
                                </td>
                                <td>
                                    @foreach ($project->procurement->toArray() as $key=>$attribute)
                                        @if($key !='deleted_at')
                                            @if($project->procurement[$key] == null)
                                                {{ $key }}, &nbsp
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
    <style>
        table{
            font-size: 13px;
        }
        table, th, td,tr {
            border: 2px solid black;
        }
    </style>
    <script src="{{ asset('/public/js/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('/public/js/bootstrap.min.js') }}"></script>
<?php


?>