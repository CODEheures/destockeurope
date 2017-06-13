<?php

return [

    'title' => 'Legal informations',

    'presentation' => '
        <h2 class="ui dividing header">Presentation of the website</h2>
        <p>The website <a href="'. route('portal') .'">'. route('portal').'</a>, here in after referred to as 
        "'. env('LEGAL_COMPAGNY_PSEUDONAME'). '" offers a service of deposit and consultation of classifieds on the 
        Internet more specifically for the professionals.
        </p>

        <p>The site is edited by <b>'. env('LEGAL_COMPAGNY_NAME').'</b>
            <small>(with capital of '. env('LEGAL_COMPAGNY_CAPITAL') .')</small>,
            <br/>Siret: Registered with the Trade and Companies Register of Tours (France) under number '. env('LEGAL_COMPAGNY_SIRET') .'.
            <br/>The head office:'. env('LEGAL_COMPAGNY_ADDRESS') .'.
            <br/>Phone number: '. env('LEGAL_COMPAGNY_PHONE').'.
            <br/>European Intracommunity VAT number: '. env('TVA_REQUESTER_VAT_NUMBER').'.
        </p>

        <p>The editor of '. env('LEGAL_COMPAGNY_PSEUDONAME').' is
            '. env('LEGAL_COMPAGNY_PUBLICATION_DIRECTOR').'.
        </p>

        <p>The webmaster of '. env('LEGAL_COMPAGNY_PSEUDONAME').' is
            '. env('LEGAL_COMPAGNY_WEBMASTER') .'.
        </p>

        <p>The host of '. env('LEGAL_COMPAGNY_PSEUDONAME').' is
            '. env('LEGAL_COMPAGNY_HOST').'.
        </p>',

    'useConditions' => '
        <h2 class="ui dividing header">Terms of Use</h2>
        <p>
            Access to the site, its consultation and its use are subject to the unreserved acceptance of these 
            General Conditions of Use of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' available at the following address:
            <a href="'. route('cgu').'">'. route('cgu').'</a>
        </p>',

    'personnalDatas' => '
        <h2 class="ui dividing header">Personal datas</h2>
        <p>
            In France, personal data are protected by Act No. 78-87 of 6 January 1978, Law No. 2004-801 of 6 August 2004
            , Article L. 226-13 of the Criminal Code and the European 24 October 1995.
        </p>
        <p>
            When using the Website, the following may be collected: the URL of the links through which the user has 
            accessed the Website, the user\'s access provider, the protocol address (IP) of the user.
        </p>
        <p>
            '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' ensures that all personal data within the meaning of Law No 78-17 of 
            6 January 1978 as amended may be collected in connection with the provision of the Service 
            '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' shall be in the context of a treatment strictly necessary for the 
            provision of the said Service '. env('LEGAL_COMPAGNY_PSEUDONAME').'.
        </p>
        <p>
            In accordance with the provisions of Article 38 et seq. Of Law 78-17 of 6 January 1978 relating to data 
            processing, files and freedoms, every user has the right to access, rectify and oppose personal data In 
            writing and signed, accompanied by a copy of the identity document with the signature of the holder of the 
            exhibit, specifying the address to which the reply should be sent.
        </p>
        <p>
            No personal information of the user of the Website is published without the knowledge of the user, 
            exchanged, transferred, transferred or sold on any medium to third parties. Only the assumption of the
            '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' and of its rights would allow the transmission of said information 
            to the potential purchaser who would in turn be required to keep the data and keep it alive with respect 
            to the user of the Website.
        </p>
        <p>
            The above-mentioned site is declared to the CNIL under number '. env('LEGAL_CNIL_NUMBER') .'.
        </p>
        <p>
            The databases are protected by the provisions of the law of 1 July 1998 transposing Directive 96/9 of 11 
            March 1996 on the legal protection of databases.
        </p>',

    'contact' => '
        <h2 class="ui dividing header">Contact US</h2>
        <h3 class="ui header">For the public</h3>
        <p>For any question about the company or any request not from the public authorities, we invite you to use our 
            contact form by clicking on the link: <a href="'. route('contact').'">' . route('contact'). '</a>. You can 
            explain your situation in detail. The dedicated team will take all necessary measures.
        </p>
        <h3 class="ui header">Service requisition</h3>
        <p>A requisition service responds only to requests from public authorities (gendarmerie, police ...).
            <br/>For Judicial Requisitions and Communication Rights, please send us the right of communication (on 
            dated, signed and stamped letterhead) or the requisition (dated, signed and stamped) specifying the 
            reference of the advertisement , The advertiser\'s email address and / or his phone number, attached as 
            an e-mail to <a href="#">'. env('SERVICE_MAIL_REQUISITION'). '</a> (response time is approximately 24 hours 
            on business day).
            <br/>The Advert reference is the number in the internet address of the Advert presentation page.
            <br/>Example : :randomAdvertUrl , reference is :randomAdvertId.
            <br/>We will send you the requested information as soon as possible. This research is done without charge.
            <br/>To simplify our procedures, please indicate in your requisition the email address to which you send our
             answer.
        </p>'



];
