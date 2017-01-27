<div class="pdfFooter">
    <div class="infos">
        <p>
            {{ env('LEGAL_COMPAGNY_NAME') }}<br/>
            {{ env('LEGAL_COMPAGNY_ADDRESS') }}<br />
            {{ env('LEGAL_COMPAGNY_PHONE') }}<br />
            {{ env('LEGAL_COMPAGNY_CONTACT') }}
        </p>
    </div>
    <div class="copyright">
        <p class="page">{{ trans('strings.pdf_footer_page') }} <span class="page">{PAGENO}</span> {{ trans('strings.pdf_footer_on') }} <span class="topage">{nbpg}</span></p>
        <div class="suiteLogo">
            <span class="htmlentity">&copy;</span>{{ env('LEGAL_COPYRIGHT') }}<span class="logo"></span>
        </div>
    </div>
</div>