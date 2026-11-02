  <!-- For exporting  data -->
  @if( count($data) > 0)
  <?php //dd($data); ?>
  <table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable1">
      <thead>
          <tr>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> ID</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Activity</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Department</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Process<br /> Stage</span></th>

              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Environmental<br /> Aspects</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Potential<br /> Impact</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Significance</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Reviewed<br />Date</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Added<br /> Date</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;width: 50px;"> Team</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Emergency</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Occurrence Probability</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Frequency of Detection</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Nature of Consequence</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Legal/Regulatory requirement</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Quantity</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Control Measure</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Total</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Review Control</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Posted By(Employee ID)</span></th>
              <th class="text-center" style="font-size:11px;width: 150px;"><span style="font-size:11px;">Reviewed By(Employee ID)</span></th>
          </tr>

      </thead>

      @foreach($data as $value)
      <?php
                                    $rr=$value->Eid;
                                    ?>
      <tbody>
          <tr>

              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->count }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;word-wrap:break-word;">{{ $value->activity }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
          </tr>
          @foreach($data2[$value->department] as $dat)
          <?php
                                       // dd($dat);
                                        $HS=$dat->HS;
                                        $color=null;
                                        ?>
          @if ($HS == "HS")
          <?php $color="red";?>
          @elseif($HS == "MS")
          <?php $color="orange";?>
          @else
          <?php $color="#40ff00";?>
          @endif
          @if( $dat->Eid==$value->Eid)
          <tr>
              <td></td>
              <td></td>
              <td></td>
              @if($dat->status=="Rejected")
              <td class="text-center " style="font-size:12px;width: 50px; color:red"><span style="font-size:11px;width: 50px; color:red">{{ $dat->status }}</span></td>
              @elseif($dat->status=="Review Pending")
              <td class="text-center " style="font-size:12px;width: 50px; color:orange"><span style="font-size:12px;width: 50px; color:orange">{{ $dat->status }}</span></td>
              @else
              <td class="text-center " style="font-size:12px;width: 50px; color:#10c505"><span style="font-size:12px;width: 50px; color:#10c505">Active Aspect</span></td>
              @endif
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->enviaspects }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->potential }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;" bgcolor='<?=$color?>'><span style="font-size:11px;width: 50px;background-color:<?=$color?>">{{ $dat->HS }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->reviewed_at }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->created_at }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->team }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->ER }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->A }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->B }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->C }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->D }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->E }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->total }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->control }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->posted_by }}</span></td>
              <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->reviewed_by }}</span></td>
          </tr>
          @endif
          @endforeach

      </tbody>
      @endforeach

  </table>
  @endif

  <script type="text/javascript">
      var tab_text

      function fnCovertTable(type) {

          if (document.getElementById('tabselected').value == 'a1') {
              exptable = "headerTable1";
              pdfvalue = "tvalue1";
          }

          tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
          var textRange;
          var j = 0;
          var i = 0;
          tab = document.getElementById(exptable); // id of table
          for (j = 0; j < tab.rows.length; j++) {
              tab_text = tab_text + "<tr>"
              for (i = 0; i < tab.rows[j].cells.length - 13; i++) {
                  tab_text = tab_text + "<td>"
                  tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
              }
              tab_text = tab_text + "</tr>";
          }

          tab_text = tab_text + "</table>";
          tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
          tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
          tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
          if (type == 'excel') {
              tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
              var textRange;
              var j = 0;
              var i = 0;
              tab = document.getElementById(exptable); // id of table
              for (j = 0; j < tab.rows.length; j++) {
                  tab_text = tab_text + "<tr>"
                  tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
              }

              tab_text = tab_text + "</table>";
              tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
              tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
              tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
              var ua = window.navigator.userAgent;
              var msie = ua.indexOf("MSIE ");
              if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
              {
                  txtArea1.document.open("txt/html", "replace");
                  txtArea1.document.write(tab_text);
                  txtArea1.document.close();
                  txtArea1.focus();
                  sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
              } else //other browser not tested on IE 11
                  sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
              return (sa);
          } else if (type == 'word') {
              var ua = window.navigator.userAgent;
              var msie = ua.indexOf("MSIE ");
              if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
              {
                  txtArea1.document.open("txt/html", "replace");
                  txtArea1.document.write(tab_text);
                  txtArea1.document.close();
                  txtArea1.focus();
                  sa = div.innerHTML.document.execCommand("SaveAs", true, "Setilas.doc");
              } else //other browser not tested on IE 11
                  sa = window.open('data:application/msword,' + encodeURIComponent(tab_text));

              return (sa);
          } else if (type == 'print') {
              var oldPage = document.body.innerHTML;
              document.body.innerHTML = tab_text;
              window.print();
              document.body.innerHTML = oldPage;
          } else if (type == "screenshot") {
              oldpage = document.body.innerHTML;
              document.body.innerHTML = tab_text;
              document.body.innerHTML = document.body.innerHTML + "<br/><button value='10' style='float:right'  class='btn btn-success' onclick='saveme()' id='save' download='file-name.png'>Save</button><span></span><button style='float:right' class='btn btn-success' onclick='back()'>Back</button><a id='blank'></a>";
          } else if (type == "pdf") {
              tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
              var textRange;
              var j = 0;
              var i = 0;
              tab = document.getElementById(exptable); // id of table
              tab_text = tab_text + "<tr>"
              for (i = 0; i < tab.rows[0].cells.length - 13; i++) {
                  tab_text = tab_text + "<th>"
                  tab_text = tab_text + tab.rows[0].cells[i].innerHTML + "</th>";
              }
              tab_text = tab_text + "</tr>"
              for (j = 1; j < tab.rows.length; j++) {
                  tab_text = tab_text + "<tr>"
                  for (i = 0; i < tab.rows[j].cells.length - 13; i++) {
                      tab_text = tab_text + "<td>"
                      tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
                  }
                  tab_text = tab_text + "</tr>";
              }

              tab_text = tab_text + "</table>";

              tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
              tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
              tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params


              document.getElementById(pdfvalue).value = tab_text;
              return true;

          } else if (type == 'magnify') {
              tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
              var textRange;
              var j = 0;
              var i = 0;
              tab = document.getElementById('mainTable'); // id of table
              for (j = 0; j < tab.rows.length; j++) {
                  tab_text = tab_text + "<tr>"
                  tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
              }

              tab_text = tab_text + "</table>";
              tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
              tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
              tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
              oldpage = document.body.innerHTML;
              var div1 = document.createElement('div');
              div1.setAttribute("id", "div1");
              var div2 = document.createElement('div');
              div2.setAttribute("id", "div2");
              div1.innerHTML = tab_text;
              div2.innerHTML = "<button type='button' class='btn btn-success'  onclick='back()'>Reset</button>"
              document.body.innerHTML = ''
              document.body.appendChild(div1);
              document.body.appendChild(div2);

          }
      }

  </script>
