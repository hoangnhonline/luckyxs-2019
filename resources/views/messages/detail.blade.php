@extends('layout')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin">
    <div class="card">
      <div class="card-body">
      	<a href="{{ route('messages.list') }}" class="btn btn-inverse-light btn-sm">Quay lại</a>
        <h3 class="text-primary" style="margin-top: 10px;">{!! $detail->content !!}</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th width="1%">
                  #
                </th>
                <th>
                  Đài
                </th>
                <th>
                  Loại đề
                </th>
                <th>Số</th>
                <th style="text-align: right;">
                  Số tiền
                </th>
                <th style="text-align: right;">
                  Tổng trước cò
                </th>
                <th style="text-align: right;">
                  Tổng sau cò
                </th>                
              </tr>
            </thead>
            <tbody>
              @php
              $i = 0;
              @endphp
              @if($betList->count() > 0)
              @php $total = 0; @endphp
              @foreach($betList as $bet)
              @php 
              $i++;
              @endphp
              <tr>
                <td class="font-weight-medium">
                  {{ $bet->id }}
                </td>
                <td>
                	<span style="text-transform: uppercase;">{{ $bet->str_channel }}</span>
                </td>
                <td>                                 
                 	{{ $bet->betType->content }}                  
                </td>
                <td>
                  @if($bet->str_number)
                  {{ $bet->str_number }}
                  @else                  
                  {{ $bet->number_1 }}
                  @if($bet->number_2)
                  -{{ $bet->number_2 }}
                  @endif
                  @endif
                	                
                </td>
                <td align="right">
                	{{ $bet->price }}
                </td>
                <td align="right">
                  <?php 
                  $total += $totalRow = $bet->calTotal($bet->id);
                  //$total += $bet->total;
                  ?>
                	<!-- {{ number_format($bet->total) }} -->
                  {{ str_replace('.0', '', number_format($totalRow, 1)) }}
                </td>
                <td align="right">
                	
                </td>                
              </tr>
              @endforeach
              <tr>
                <td colspan="6" style="text-align: right;">
                  <h1>{{ str_replace('.0', '', number_format($total, 1)) }}</h1>
                </td>
                <td></td>
              </tr>
              @endif

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div> 
@stop
@section('js')
<script type="text/javascript">
  $(document).ready(function(){
    $('table tr').click(function(){
      $(this).remove();
    });
  });
</script>
@stop