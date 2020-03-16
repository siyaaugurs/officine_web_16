<table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Method Name</th>
                    <th>Number of hits</th>
                </tr>
            </thead>
            <tbody>
              @forelse($api_monitoring as $m_view)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m_view->method_name }}</td>
                    <td>{{ $m_view->total_hits }}</td>
                  </tr>
              @empty
                <tr>
                    <td colspan="3" class="danger">No records found !!!</td>
                  </tr>
              @endforelse     
             </tbody>
        </table>