@extends('frontend.templates.pagestr')

@section('css_before')
    <link href="{{ asset('assets/frontend/css/dragula/dragula.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('assets/frontend/css/dragula/example.css') }}" rel='stylesheet' type='text/css' />
    <title>dragula</title>
@endsection

@section('page_title')
    login
@endsection

@section('full_content')
<script src="{{ asset('assets/frontend/js/dragula/dragula.js') }}"></script>

<div class="row">
    <div class="col-md-12">
        <div class='parent'>
            <label for='hy'>Move stuff between these two containers. Note how the stuff gets inserted near the
                mouse
                pointer? Great stuff.</label>

            <div class='wrapper'>
                <div id='left-defaults' class='container'>
                    <p>Trả lời bên lề Hội nghị Ngoại giao lần thứ 32 về hợp tác trong lĩnh vực bán dẫn giữa Việt Nam
                        và Mỹ, Đại sứ Việt Nam tại Mỹ Nguyễn Quốc Dũng cho biết, để nâng cấp quan hệ Việt Nam-Mỹ lên
                        Đối tác Chiến lược toàn diện (ĐTCLTD) thì cần có những lĩnh vực có ưu tiên, có trọng tâm.
                    </p>
                    <p>Xuất phát từ nhu cầu và mong muốn của Việt Nam thì phía Mỹ cũng nhất trí chọn lĩnh vực công
                        nghệ cao, trong đó có chất bán dẫn sẽ là hợp tác trọng tâm trong quan hệ hợp tác giữa hai
                        nước.</p>
                    <p>Đây vừa là mục tiêu vừa là thỏa thuận giữa lãnh đạo cấp cao nhất của hai nước. Điều này đã
                        tạo động lực cho các doanh nghiệp, thu hút được sự quan tâm của các doanh nghiệp và tạo một
                        hiệu ứng rất tốt, Đại sứ Nguyễn Quốc Dũng nói.</p>
                    <div class="VCSortableInPreviewMode alignRight" data-back="#FFFBF2" data-border="#F2D1AA"
                        data-text-color="#333333"
                        style="background-color:#FFFBF2;border-color:#F2D1AA;display:block;color:#333333;"
                        id="ObjectBoxContent_1703173254529" type="content" data-style="align-right">
                        <div placeholder="Nhập nội dung..." class="" data-gramm="false" wt-ignore-input="true">
                            <p>Tuyên bố chung về nâng cấp quan hệ Việt Nam - Mỹ lên Đối tác Chiến lược Toàn diện ghi
                                rõ, Việt Nam và Mỹ quyết định đẩy mạnh hợp tác về khoa học, công nghệ và đổi mới
                                sáng tạo trong lĩnh vực số, coi đây là đột phá mới của quan hệ Đối tác Chiến lược
                                Toàn diện.</p>
                            <p>Mỹ khẳng định cam kết tăng cường hỗ trợ Việt Nam đào tạo và phát triển lực lượng lao
                                động công nghệ cao. Ghi nhận tiềm năng to lớn của Việt Nam trở thành quốc gia chủ
                                chốt trong ngành công nghiệp bán dẫn, hai Nhà Lãnh đạo ủng hộ sự phát triển nhanh
                                chóng của hệ sinh thái bán dẫn tại Việt Nam và hai bên sẽ tích cực phối hợp nhằm
                                nâng cao vị trí của Việt Nam trong chuỗi cung bán dẫn toàn cầu. Theo đó, Việt Nam và
                                Mỹ tuyên bố khởi động các sáng kiến phát triển nguồn nhân lực trong lĩnh vực bán
                                dẫn, trong đó Chính phủ Mỹ sẽ cấp khoản tài trợ gieo mầm ban đầu trị giá 2 triệu
                                USD, cùng với các khoản hỗ trợ từ Chính phủ Việt Nam và khu vực tư nhân trong tương
                                lai.</p>
                        </div>
                    </div>
                    <p>Lý giải về nguyên nhân các doanh nghiệp (DN) Mỹ quan tâm đến Việt Nam để đầu tư, Đại sứ Việt
                        Nam tại Mỹ cho rằng, xuất phát điểm là yếu tố chính trị, các DN Mỹ thấy điều kiện chính trị
                        chín muồi thì họ quan tâm.</p>
                    <p>"Các DN Mỹ cũng có chiến lược của họ, họ cũng quan tâm để thực hiện các chiến lược về phát
                        triển, đồng thời cũng muốn tận dụng chính sách chính phủ của họ tạo ra. Khi họ nhận thấy
                        được xu hướng đó thì họ quan tâm đến Việt Nam", ông Dũng nói.</p>
                    <p>Khi vào Việt Nam, các DN Mỹ lại nhận thấy Việt Nam có tiềm lực rất tốt, thứ nhất là về con
                        người - Việt Nam có lực lượng lao động trẻ rất sáng tạo, có tài năng, hiện nay cũng có rất
                        nhiều người trẻ làm việc ở các công ty công nghệ của Mỹ; thứ hai là sự quyết tâm rất cao của
                        chính phủ Việt Nam, và sự sẵn sàng của các tổ chức.</p>
                    <p>"Đối với họ đấy là những điều kiện lý tưởng mà họ rất là muốn tận dụng", ông Dũng nhấn mạnh.
                    </p>
                    <p>Đại sứ Nguyễn Quốc Dũng cũng chia sẻ, Việt Nam xác định khoa học công nghệ, trong đó có lĩnh
                        vực bán dẫn, là thế mạnh của Mỹ, đồng thời nhận thấy phía bạn cũng có nhu cầu trong đa dạng
                        hóa nguồn cung, muốn tìm các đối tác đầu tư nên hợp tác trong lĩnh vực này là mục tiêu ngay
                        từ lúc sang và tiếp cận với phía bạn.</p>
                    <p>"Rất may là tôi có một ông bạn cũ từ khi tôi làm APEC năm 2006. Đó là John Neffeur, hiện là
                        Chủ tịch Hiệp hội Công nghiệp bán dẫn Mỹ (SIA) nên ông ấy rất ủng hộ tôi, và sẵn sàng hỗ trợ
                        những đề nghị của chúng ta", Đại sứ Việt Nam tại Mỹ tiết lộ.</p>
                    <p>Đại sứ Nguyễn Quốc Dũng gặp lại ông John Neffeur tháng 10/2022 và tháng 12 cùng năm, ông John
                        Neffeur đến Việt Nam và kéo theo một loạt DN. Và sau đó ông John Neffeur liên tục có các
                        chuyến thăm Việt Nam, cùng với đó là các DN.<br></p>
                    <p>Sau đó, trong khoảng đầu năm 2023, hai nước đã có một cuộc họp trực tuyến lớn giữa một bên là
                        Bộ trưởng Bộ Kế hoạch và Đầu tư Nguyễn Chí Dũng chủ trì và bên kia là ông John Neffeur, kèm
                        theo mỗi bên là các DN, các tổ chức, các trường ĐH. Từ đấy, các DN của Mỹ rất quan tâm, và
                        sau đó "ông lớn" Marvell cũng quyết định đầu tư vào Việt Nam, chỉ trong thời gian rất ngắn.
                        Đặc biệt là sau chuyến thăm Việt Nam của Tổng thống Joe Biden (tháng 9/2023) nữa thì các DN
                        cũng bày tỏ quan tâm, Đại sứ Nguyễn Quốc Dũng chia sẻ.</p>
                    <p>Đại sứ Việt Nam tại Mỹ cũng kỳ vọng, các chuyến thăm vừa qua như của ông Jensen Huang, giám
                        đốc điều hành (CEO) và là người sáng lập chính của Nvidia chắc chắn sẽ nhiều hơn vì khi
                        Nvidia quan tâm đến Việt Nam sẽ kéo theo các DN khác quan tâm.</p>
                    <div class="VCSortableInPreviewMode alignCenter" data-back="#FFFBF2" data-border="#F2D1AA"
                        data-text-color="#333333"
                        style="background-color:#FFFBF2;border-color:#F2D1AA;display:block;color:#333333;"
                        id="ObjectBoxContent_1703153998582" type="content">
                        <div placeholder="Nhập nội dung..." class="" data-gramm="false" wt-ignore-input="true">
                            <p>Hiệp hội Công nghiệp bán dẫn Mỹ (SIA) được thành lập năm 1977, là hiệp hội đại diện
                                cho ngành công nghiệp bán dẫn Mỹ, một trong những ngành xuất khẩu hàng đầu và là
                                động lực chính cho sức mạnh kinh tế, an ninh quốc gia và khả năng cạnh tranh toàn
                                cầu của Mỹ.<br></p>
                            <p>Đến nay, SIA quy tụ mạng lưới các doanh nghiệp thành viên chiếm tới 99% doanh thu
                                ngành bán dẫn Mỹ, trong đó 2/3 là các doanh nghiệp bán dẫn nước ngoài. SIA đã có
                                tiếng nói tích cực để phía Mỹ thúc đẩy hợp tác phát triển hệ sinh thái bán dẫn tại
                                Việt Nam.<br></p>
                            <p>Trong cuộc gặp với Thủ tướng Phạm Minh Chính vào ngày 7/12/2023, ông John Neffeur,
                                Chủ tịch Hiệp hội Công nghiệp bán dẫn Mỹ (SIA) cho biết Mỹ đang có cơn khát nhân lực
                                chất bán dẫn và ngay từ trong đại dịch COVID-19, nguồn nhân lực Việt Nam đã là nguồn
                                bù đắp quan trọng cho sự thiếu hụt này.</p>
                            <p>Ông khẳng định Việt Nam là điểm đến hấp dẫn nhất của các nhà đầu tư Mỹ trong lĩnh vực
                                bán dẫn và có thể đóng vai trò đối tác chiến lược trong cung cấp nguồn nhân lực.</p>
                            <p>Chủ tịch SIA cho hay các doanh nghiệp Mỹ hào hứng chờ đón chiến lược quốc gia về bán
                                dẫn của Việt Nam và mong muốn Việt Nam sẽ đóng vai trò quan trọng hơn nữa trong
                                chuỗi cung ứng toàn cầu, đặc biệt là khâu thiết kế chip vốn không đòi hỏi nhiều đầu
                                tư so với sản xuất.</p>
                        </div>
                    </div>
                    <p><br></p>
                    <!--Thu Dec 21 2023 17:16:52 GMT+0700 (Indochina Time) -- Thu Dec 21 2023 17:16:52 GMT+0700 (Indochina Time) -- Thu Dec 21 2023 17:24:52 GMT+0700 (Indochina Time)-->
                    <!--Thu Dec 21 2023 18:30:00 GMT+0700 (Indochina Time) -- Thu Dec 21 2023 18:30:00 GMT+0700 (Indochina Time) -- Thu Dec 21 2023 17:27:36 GMT+0700 (Indochina Time)-->

                    {{--
                </div> --}}
                </div>

                <div id='right-defaults' class='container'>

                </div>
            </div>

            <pre>
                <code>
                    dragula([
                        document.getElementById('left-defaults'), 
                        document.getElementById('right-defaults')
                    ]);
                </code>
            </pre>
        </div>
        <script>
            dragula([document.getElementById('left-defaults'), document.getElementById('right-defaults')]);
        </script>
    </div>
</div>
@endsection

{{-- https://github.com/bevacqua/dragula#drakeon-events --}}
