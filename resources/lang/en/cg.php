<?php

return [

    'tempo' => '
        <p class="ui orange big label">This company is in the process of being created and this website is not yet open 
            to the public. This access is only for "live" test purposes for web developers
        </p>',

    'definitions' => '
        <h2  class="ui dividing header">Preamble: Definitions</h2>
        <ul>
            <li>Advertiser: Any natural or legal person submitting an Announcement via the service 
                '.env('LEGAL_COMPAGNY_PSEUDONAME'). '.</li>
             <li>Announcement: Composition of the texts, images and videos published by the advertiser via the 
                service '.env('LEGAL_COMPAGNY_PSEUDONAME'). '.</li>
             <li>Pro account: Free secured area of an advertiser allowing access to the data personal and free to 
                 advertise or pay for ads</li>
             <li>Website: means the website operated by '.env('LEGAL_COMPAGNY_PSEUDONAME'). ', notably all URLs of 
                 the domain name ' .Substr (Illuminate\Support\Facades\Request::root (), 7).'
             </li>
        </ul>',

    'diffusionRulesTitle' => 'Adverts dissemination rules',

    'diffusionsRules' => '
            <p>The advertiser undertakes to only distribute Announcements in its own name and on its own behalf. 
                Thus, unless prior and express agreement of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', Advertiser can not 
                use the Service'. env('LEGAL_COMPAGNY_PSEUDONAME'). ' to broadcast advertisements on behalf of and / or 
                on behalf of a third party.
            </p>
            <p>'. env('LEGAL_COMPAGNY_PSEUDONAME').' reserves the right to:</p>
            <ul>
                <li>Delete any Advertisement issued by the Advertiser in the name and / or on behalf of a third party, without any refund and / or compensation being claimed by the Advertiser.</li>
                <li>Remove without notice the "Pro Account" and any Advertisements in progress of a Advertiser that would contravene this prohibition and without any refund and / or compensation can be claimed to him by the Advertiser.</li>
                <li>To prohibit the use of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' for the purpose of distributing the Announcement to the Advertiser which contravenes this prohibition and this without any refund and / or indemnification can not be claimed to him by the Advertiser.</li>
            </ul>
            <p>
                Without this creating an obligation to verify the content, '. env ('LEGAL_COMPAGNY_PSEUDONAME'). ' 
                reserves the right to accept, refuse or delete any ad filed or modified by the Advertiser.
            </p>
            <p>'. env('LEGAL_COMPAGNY_PSEUDONAME').' reserves the right to refuse or remove any Advert that contravenes 
                the provisions of these <a href="'. route('cgu').'">Terms & Conditions</a>, which does not comply with 
                this paragraph and the rules of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', or whose content is 
                clearly contrary to the legal and regulatory provisions in force, as detailed in the 
                <a href="'. route('cgu').'">Terms & Conditions</a> To the article « Commitment of the advertiser ».
            </p>
            <p>
                In the event that the Advert contains a photograph, '. env ('LEGAL_COMPAGNY_PSEUDONAME'). ' reserves 
                the right not to broadcast the Announcement:
            </p>
            <ul>
                <li>If the quality of the picture is insufficient.</li>
                <li>If the photo is contrary to the rules of diffusion of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME').'.</li>
                <li>If the photograph does not represent the object object of the Advert and is limited to a representation of the logo and / or commercial visual of the Advertiser.</li>
            </ul>
            <p>
                In the event that the Ad contains a video, '. env ('LEGAL_COMPAGNY_PSEUDONAME'). ' reserves the right 
                not to broadcast the Announcement:
            </p>
            <ul>
                <li>If the quality of the video is insufficient.</li>
                <li>If the video is contrary to the rules of diffusion of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME').'.</li>
                <li>If the video does not represent the object object of the Advert and is limited to a representation of the logo and / or commercial visual of the Advertiser.</li>
            </ul>
            <p>
                In no case shall an Advert be used to broadcast any commercial message other than the presentation of 
                any particular property. If an Ad is refused, before it is put online, by 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', the Advertiser will be notified by email sent to the address 
                indicated when creating the Pro Account, no payment will be requested from the advertiser.
                <br />Such refusal does not entitle the Advertiser to any compensation. Every published Ad is shown on 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' For a period of '. env('ADVERT_LIFE_TIME').' renewable days, 
                except for early withdrawal due to the Advertiser or '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' particularly
                 because of illegal content.
            </p>
            <p>
                Adverts are classified as'. env ('LEGAL_COMPAGNY_PSEUDONAME'). ' in chronological order, according to 
                the date and time of their posting. Consequently, the Advertiser acknowledges and accepts that the 
                presence at the top of his listing is only provisional.
            </p>',

    'v' => [
        'title' => 'General Conditions of Sale',
        'object' => '
            <h2  class="ui dividing header">Article 1: Objet</h2>
            <p>
                These <a href="'.route('cgv').'">General Conditions of Sale</a> set out the conditions 
                exclusively applicable to the passage of an Order by a Advertiser from the Website.
            </p>',

        'accept' => '
            <h2  class="ui dividing header">Article 2: Acceptance</h2>
            <p>
                The advertiser declares having read the present <a href="'.route('cgv').'">General Conditions of Sale</a> and 
                <a href="'.route('cgu').'">Terms and Conditions</a> and accept them expressly, without reservation 
                and / or modification of any kind whatsoever. The advertiser waives the right to use its own general 
                conditions of purchase.
            </p>
            <p>
                Any contrary condition opposed by the Advertiser will therefore be absent '.env('LEGAL_COMPAGNY_PSEUDONAME'). '
                , What is the meaning of regardless of when it may have been brought to its attention.
            </p>
            <p>
                The fact that '.env('LEGAL_COMPAGNY_PSEUDONAME').' does not prevail at any time given of any of the present 
                <a href="'.route('cgv').'">General Conditions of Sale</a> can not be interpreted as a waiver of the right to 
                rely on the any of said conditions.
            </p>',

        'ordering' => '
            <h2  class="ui dividing header">Article 3: Placement of an order</h2>
            <h3 class="ui header">General rules</h3>
            <p>
                The profit of any Order (Insertion Fee, Paid Option (s)), is personal to the Advertiser who made it and can 
                not be transferred, transferred without the agreement of '. env ('LEGAL_COMPAGNY_PSEUDONAME'). '. No refund 
                is possible after the execution of any order placed and validated by '.env('LEGAL_COMPAGNY_PSEUDONAME').'.
            </p>
            
            <h3 class="ui header">Time of order, payment and billing</h3>
            <p>The payment is made by paypal or by credit card at the time of the deposit of the announcement</p>
            <p>The rates are quoted in euros and are expressed net of tax (excluding taxes)</p>
            <p>The rates are communicated to the Advertiser on simple request and are available in the present 
                <a href="'.route('cgv').'">General Conditions of Sale</a>.
            </p>
            <p>
                The fee for the insertion, renewal and each Pay Option is the price in effect on the day of the Order by the
                 Advertiser. '.env('LEGAL_COMPAGNY_PSEUDONAME').' reserves the right to modify its rates at any time.
            </p>
            <p>If the ad is refused by the moderation service of '.env('LEGAL_COMPAGNY_PSEUDONAME').', due to its 
                non-compliance with the dissemination rules, the payment by credit card or paypal will be canceled and the 
                Advertiser not debited. The Advertiser will be notified by email.
            </p>
            <p>If the advertisement is published by the service moderation of '.env('LEGAL_COMPAGNY_PSEUDONAME').', the 
                payment will be captured and the ad will be posted on the Website.</p>
            <p>
                No reimbursement is possible after performance of the service or validation by the service moderation of 
                '.env('LEGAL_COMPAGNY_PSEUDONAME').'.
            </p>
            <p>
                The invoice amount, expressed in euro excluding VAT, will be increased by VAT and / or any other tax payable
                 by the Advertiser, at the rate in force on the date of publication.
            </p>
            <p>
                An invoice is automatically sent by email to the advertiser after validation of the announcement by the 
                service moderation of '.env('LEGAL_COMPAGNY_PSEUDONAME').'.
            </p>',

        'description' => '
            <h2 class="ui dividing header">Article 4: Description and prices</h2>
            <h4 class="ui header">Pricing</h4>
            <p>
                The prices applied to the posting of a new advertisement are composed of the sum:
            </p>
            <ul>
                <li>Insertion fees: these are the fees applied to each ad when you put it online, regardless of the category
                 and the pay options you choose</li>
                <li>Paid options: these are options « Video », « extra photos », « Urgent », « to Negotiate »</li>
            </ul>
            <p>
                The rate applied to the renewal of an Advert is fixed and does not depend on the options chosen when the 
                Advert was first put online
            </p>
            
            <h4 class="ui header">Insertion fee</h4>
            <p>To date, insertion fees are offered.</p>
            
            <h4 class="ui header">Paying Options</h4>
            <p>The paid options are subscribed at the time of the deposit of the Advert and for the duration of the 
                Announcement of the Advert for a maximum duration of '. env('ADVERT_LIFE_TIME'). ' days
            </p>
            <p>A paid option is only available for one Advert. As a result, it is not possible to transfer the benefit of a 
                paid option from one Advert to another.
            </p>
            <p>It is possible to subscribe to several paid options for the same Advert.</p>
            <p>The price of each paid option varies depending on the option.</p>
            <p>Subscribing to a paid option does not extend the duration of the Advert.</p>
            <p>In the case of early withdrawal of the Advertisement (either because of the Advertiser or because of 
                '. env('LEGAL_COMPAGNY_PSEUDONAME').', in particular in case of non-compliance with these GCU or the 
                dissemination Service '.env(' LEGAL_COMPAGNY_PSEUDONAME').' or the expiration of its duration, the Paid 
                Option ceases to have effect.
            </p>
            <p>Renewing an Announcement extends the term of all its timely paid options.</p>
            <p>
                Advertiser acknowledges and agrees that any Advertisement placed in a category not corresponding to the 
                proposed product or service may be deleted at any time by ' .env(' LEGAL_COMPAGNY_PSEUDONAME').' without 
                any indemnity or right to reimbursement of the sums incurred for the purposes of subscription to Paying Options.
            </p>',

        'options' => [

            'urgent' => '
                <h5 class="ui header">Add the option « Urgent »</h5>
                <p>Option price: ' . \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsUrgent(true),'EUR',true) .' Excl. VAT</p>
                <p>This option allows:</p>
                <ul>
                    <li>To display an « urgent » logo on the Advert</li>
                    <li>To be part of the « urgent » global filter criterion in the result search</li>
                </ul>
                <p>In order to benefit from it, it is necessary to select it directly on the page of creation of the 
                    Announcement by checking the option « URGENT ».
                </p>
                <p>
                    If the Advert is validated by the moderation service of '. Env ('LEGAL_COMPAGNY_PSEUDONAME'). ', it 
                    will be posted on the Website and the Universal iPhone / iPad and Android Applications and will be 
                    marked with a logo in the results list.
                </p>',

            'toNegociate' => '
                <h5 class="ui header">Add the option « to Negociate »</h5>
                <p>Price of the option: offered</p>
                <p>This option allows:</p>
                <ul>
                    <li>To display a "to negociate" logo on the Advert instead of the sale price of the item</li>
                    <li>To be part of the global filter criterion "to negotiate" in the result search</li>
                </ul>
                <p>In order to benefit from it, it is necessary to select it directly on the page of creation of the 
                    Advert by checking the option « to negociate ».
                </p>
                <p>
                    If the Advert is validated by the moderation service of '. Env ('LEGAL_COMPAGNY_PSEUDONAME'). ', it 
                    will be posted on the Website and the Universal iPhone / iPad and Android Applications and will be 
                    marked with a logo in the results list.
                </p>',

            'video' => '
                <h5 class="ui header">Add the video option</h5>
                <p>Price of the option: '.  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostVideo(true),'EUR',true).' Excl. VAT</p>
                <p>This option allows:</p>
                <ul>
                    <li>To add a video to my Advert</li>
                </ul>
                <p>
                    In order to benefit from it, it is necessary to select it directly on the page of creation of the 
                    Advert by clicking the button « + Add a video ».
                </p>
                <p>
                    If the Advert is validated by the moderation service of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', it 
                    will be uploaded to the website and the Universal iPhone / iPad and Android applications and the 
                    video will be viewable on the dedicated page of the announcement.
                </p>
                <p>
                    The Advertiser acknowledges and agrees that due to the ergonomics of the solutions'
                    . env('LEGAL_COMPAGNY_PSEUDONAME'). ', if the advertiser\'s video does not match the app\'s 
                    adaptations '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' can not 
                    be held responsible for the non-functioning of the video of the advertiser.
                </p>
                <p>
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is in no way responsible for a video whose character(s) 
                    would be prohibited by French or European laws. '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' will be 
                    obliged to refer to the competent authorities without having to contact the advertiser.
                </p>',

            'photos1' => '
                <h5 class="ui header">Add option Additional photos</h5>
                <p>Price of the option:</p>
                <ul>',

            'photos2' => '
                <li>Advert with :compt photos: :price Excl. VAT</li>',

            'photos3' => '
                </ul>
                <p>This option allows:</p>
                <ul>
                    <li>To add '. (config('runtime.nbMaxPictures')-config('runtime.nbFreePictures')) . ' additional 
                        photographs in an Advert in addition to '. config('runtime.nbFreePictures').' free photo(s) and 
                        thus present to the maximum '. config('runtime.nbMaxPictures').' photographs in an Advert.
                    </li>
                </ul>
                <p>
                    In order to benefit from it, it is necessary to select it directly on the page of creation of the 
                    Advert by clicking the button « + Add a photo ».
                </p>
                <p>
                    If the Advert is validated by the moderation service of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', it will 
                    be uploaded to the website and the Universal Applications iPhone / iPad and Android and the photos 
                    will be visible in the dedicated page of the announcement.
                </p>
                <p>
                    The Advertiser acknowledges and agrees that due to the ergonomics of the solutions'
                    . env('LEGAL_COMPAGNY_PSEUDONAME'). ', if the advertiser\'s photo(s) does not match the app\'s 
                    adaptations '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' can not 
                    be held responsible for the non-functioning of the video of the advertiser.
                </p>
                <p>
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is in no way responsible for a photo whose character(s) 
                    would be prohibited by French or European laws. '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' will be 
                    obliged to refer to the competent authorities without having to contact the advertiser.
                </p>',

            'edition' => '
                <h4 class="ui header">Edit an Advert</h4>
                <p>Price to edit an Advert: '. \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsEdit(true),'EUR',true).' Excl. VAT</p>
                <p>
                    This option allows advertisers to edit their Adverts for the "title", "description", "category", "price"
                    , "place", "photo", "video" fields.
                    <br />The editing of the "total quantity" and "minimum sales lot" fields are FREE.
                    <br />Your Advert is automatically and FREE put back to top of list with this option.
                </p>
                <p>
                     An Advert is eligible for editing only if its remaining publication time is greater than
                    '. env('REMAINING_HOURS_FOR_EDIT_ELIGIBILITY').' hours.
                    <br />All the options already present remain vested.
                    <br />For example, if the edited Advert contains
                    '. (config('runtime.nbFreePictures')+1).' photos (thus 1 paying), then the advertiser can replace these
                    '. (config('runtime.nbFreePictures')+1).' photos without additional cost to this edition option.
                    If he wishes to add 1 and make his announcement to '. (config('runtime.nbFreePictures')+2).' photos,
                     Then the rate applicable to additional photos will be applied to the latter.
                    <br />Deleting an option during editing will make this deletion final after committing changes.
                </p>',

            'renew' => '
                <h4 class="ui header">Renew of an Advert</h4>
                <p>Price to renew an Advert: '. \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',true).' Excl. VAT</p>
                <p>
                    The renewal of an Advert extends the Advert for a period of '. env('ADVERT_LIFE_TIME').' days.
                    All of its timeless options are renewed.
                    Your Advert is automatically and FREE put back to top of list with this option.
                </p>',

            'backToTop' => '
                <h4 class="ui header">Put Back an Advert to top of the list</h4>
                <p>Price to put Back an Advert to top of the list: '.  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsBackToTop(true),'EUR',true).' Excl. VAT</p>
                <p>
                    Puting back to the top of the list will place the Advert at the top of the list of Adverts. This
                     option does not guarantee how long your Advert will stay at the top of the list.
                </p>',

            'highlight1' => '
                <h4 class="ui header">Highlight an Advert</h4>
                <p>Price to highlight an Advert:
                    <br />The price is variable. It is fixed at the time of application. It depends on the number of 
                    announcements already in the news [rated \'nbU\'].
                    <br />The calculation formula is as follows: '. config('runtime.highlightCost').'/<span style="font-size: large">&Sqrt;</span>nbU.
                    <br />Examples:
                </p>
                <ul>',

            'highlight2' => '<li>nbU= :case => price = :price Excl. VAT</li>',

            'highlight3' => '
                </ul>
                <p>
                    Highlighting lets you place the Advert in a second list next to the search results.
                    This second list consists of '. env('HIGHLIGHT_QUANTITY').' locations.
                    As the Advert is in competition with other highlight Advert already on the market, it will be 
                    chosen and placed randomly throughout the life of the option.
                    The lifetime of this option is set at '. env('HIGHLIGHT_HOURS_DURATION').' hours.
                    This option does not guarantee the number of views on the front page.
                    An Advert is eligible for this option only if its remaining publication time is greater than
                    '. env('HIGHLIGHT_HOURS_DURATION').' hours
                </p>'
        ],

        'major' => '
            <h2  class="ui dividing header">Article 5: Case of force majeure</h2>
            <p>
                Neither the Advertiser, on the one hand, nor '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', Its subcontractors 
                or suppliers, on the other hand, can not be held responsible for any delay, non-performance or other 
                breach of its obligations resulting from a case of force majeure. The following shall in particular be 
                regarded as cases of force majeure: those usually adopted by the jurisprudence of the French courts and 
                tribunals, as well as total or partial strikes, internal or external to one of the parties, a supplier 
                or subcontractor, lock-out , Blockages of means of transport or supply for any reason whatsoever, fires
                , storms, floods, water damage, governmental or legal restrictions, legal or regulatory changes in the 
                forms of marketing, blocking of telecommunication means, including The networks, and any other case 
                independent of the will of the Advertiser, of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', its subcontractors
                 or suppliers preventing the normal performance of the services.
            </p>
            <p>
                Each party shall notify the other party by registered letter with acknowledgment of receipt of the 
                occurrence of any case of force majeure.
            </p>
            <p>
                In the event of an event of force majeure, if the impediment to the normal performance of the 
                contractual obligation lasted more than one month, the parties shall be released from their reciprocal 
                obligations without any formality, without notice and without notice. That no indemnity of any kind 
                whatsoever may be claimed from the defaulting party after the sending of a registered letter with 
                acknowledgment of receipt with immediate effect.
            </p>',

        'modifications' => '
            <h2  class="ui dividing header">Article 6: Modification of <a href="'.route('cgv').'">General Conditions of Sale</a></h2>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME').' reserves the right, at any time, to modify in whole or in part 
                the <a href="'. route('cgv').'">General Conditions of Sale</a>.
            </p>
            <p>Advertisers are invited to regularly consult
                <a href="'. route('cgv').'">General Conditions of Sale</a> in order to be aware of changes made.
            </p>',

        'juridiction' => '
            <h2  class="ui dividing header">Article 7: Jurisdiction and Miscellaneous Provisions</h2>
            <p>
                Any litigation falls within the exclusive jurisdiction of the Commercial Court of Tours (37000) in 
                France, even in the event of a warranty call or multiple defendants, or in the case of urgent or 
                conservatory proceedings, request. These <a href="'. route('cgu').'">general conditions of use</a> 
                and <a href="'. route('cgv').'">general conditions of use Sale</a> are subject to French law.
            </p>
            <p>
                If any of the <a href="'. route('cgv').'">general conditions of use Sale</a> were to be found to be 
                unlawful, invalid or unenforceable for any reason whatsoever, the Would be deemed unwritten, without 
                affecting the validity of the remaining provisions which will continue to apply between the Advertisers 
                and '. env('LEGAL_COMPAGNY_PSEUDONAME'). '.
            </p>
            <p>
                It is to be noted that the present contract and these 
                <a href="'. route('cgv').'">general conditions of sale</a> are subject to the provisions of the law of 
                N ° 2004-575 of 21 June 2004, Art. 25-II and Ordinance No. 2005-674 of June 16, 2005 and Articles 
                1369-1 to 1369-2 of the Civil Code
            </p>',
    ],

    'u' => [

        'title' => 'Terms & Conditions',

        'object' => '
            <h2  class="ui dividing header">Article 1: Objet</h2>
            <p>
                These <a href="'. route('cgu').'">Terms & Conditions</a> establish the terms and conditions applicable 
                to the use of the Website by a Advertiser.
                <br /><br />The conditions for subscription of paid option(s) by the advertiser are described in the 
                <a href="'. route('cgv').'">General Conditions od Sale</a>
            </p>',

        'accept' => '
            <h2  class="ui dividing header">Article 2: Acceptance</h2>
            <p>
                The use of the Website implies the full acceptance of the general Terms & Conditions hereinafter 
                described. These Terms & Conditions are subject to change or completion at any time, so users of the 
                Website are invited to consult them on a regular basis.
            </p>',

        'using' => '
            <h2  class="ui dividing header">Article 3: Use of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME').'</h2>
            <h3 class="ui header">Pro Account Rules</h3>
            <h4 class="ui header">Creation and ownership of the Pro account</h4>
            <p>
                Any Advertiser who wishes to advertise via the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' must 
                necessarily create a "Pro Account" from the Website by completing the activation form made available on 
                the Website, and choosing its login identifiers.
            </p>
            <p>
                A Pro Account is personal to the Advertiser and can not be transferred or transferred to any third party
                 without the prior written consent of '. env('LEGAL_COMPAGNY_PSEUDONAME'). '. It is forbidden to use a 
                 Pro Account for several points of sale, each Pro Account being allocated specifically for a point of 
                 sale. There is therefore only one Pro Account per point of sale, the latter being identified by a 
                 single email address.
            </p>
            <h4 class="ui header">Orders made by the Advertiser from their Pro account</h4>
            <p>
                The Advertiser can only place an ad with or without a paid option only from its Pro Account.
            </p>
            <p>
                The Advertiser may from his Pro Account:
            </p>
            <ul>
                <li>Submit Adverts</li>
                <li>View his / her Current Adverts and their visit and favorite stats</li>
                <li>Remove adverts</li>
            </ul>
            <p>The deposit or renewal of the announcements may entail the subscription of paid options such as:</p>
            <ul>
                <li>The affixing of the Urgent Logo</li>
                <li>The publication of a Video in the announcement</li>
                <li>Additional Photos beyond '. config('runtime.nbFreePictures').' photo(s)</li>
                <li>Renewal of an Advert</li>
            </ul>
            <h4 class="ui header">Pro account termination</h4>
            <p>
                The Pro account is free and created for an indefinite period, it can be canceled at any time without 
                notice by the Advertiser by writing to the customer service by clicking on the 
                <a href = "' . route('contact').'">Contact</a>.
            </p>
            <p>
                The cancellation by a Advertiser of his Pro Account leads to the automatic deletion of the ads attached 
                to this account, which the Advertiser recognizes and accepts.
            </p>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' reserves the right, subject to a notice of 8 days from the 
                sending of an email notifying the termination of the present to the Advertiser addressed to the email 
                address indicated by the Advertiser When opening its Pro Account or subscribing to the Service 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', to modify, to interrupt or to halt the accessibility to all or 
                part of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' and / or the Website or Universal 
                Applications iPhone / iPad and Android, without being required to pay the Advertiser any compensation 
                whatsoever. The Paid Advertisement which, as a result, is not broadcast, may be refunded to the 
                Advertiser.
            </p>',

        'diffusionsRulesTitle' => '
            <h2 class="ui dividing header">Article 4: Rules for publishing and moderation of announcements</h2>',

        'advertisserEngagment' => '
            <h2 class="ui dividing header">Article 5: Advertiser\'s commitments</h2>
            <ol>
                <li>
                    The Advertiser certifies that the Advertisement, whatever its distribution, complies with all legal 
                    and regulatory provisions in force (in particular relating to advertising, competition, promotion 
                    of sales, Use of the language of the country of sale, use of personal data, prohibition of the 
                    marketing of certain goods or services), complies with the provisions of the 
                    <a href="'. route('cgu').'">Terms & Conditions</a>  and 
                    <a href="'. route('cgv').'">General Conditions of Sale</a> and the 
                    <a href ="'. route('diffusionRules'). '">Dissemination Rules</a> 
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' and does not affect the rights of third parties (including 
                    intellectual property rights and personality rights).
                    <br/><br/>The Advertiser guarantees that the content of its Adverts is strictly in conformity with 
                    the legal obligations imposed on its activity.
                    <br/><br/>Advertiser warrants to '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' be the sole and exclusive 
                    author of the text, drawings, videos, photographs etc. Composing the Announcement. Failing this, he 
                    declares to have all the rights, in particular the intellectual property rights and authorizations 
                    necessary for the diffusion of the Announcement.
                    <br/><br/>Consequently, any Advert filed and broadcast on the Service 
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' appears under the sole responsibility of the Advertiser
                    <br/><br/>As a result, the Advertiser reports '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', its 
                    subcontractors and suppliers, from all liabilities, warrants them against any claim or action in 
                    connection with the Announcement which may be brought against them by any third party and shall 
                    defray all damages as well as the costs and expenses to which they may be liable or which may be 
                    imposed on them by a settlement agreement signed by them with such third party, notwithstanding 
                    all damages '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', its subcontractors and suppliers may claim 
                    damages caused by the Advertiser.
                    <br/><br/>Advertiser acknowledges and agrees that '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is 
                    entitled to remove without notice, indemnity or right to reimbursement any Advert in the course of 
                    distribution which is not in conformity with the dessimination rules of the Service and / or which 
                    would be likely to infringe the rights of a third party or would contain Illegal content.
                    <br/><br/>
                </li>
                <li>
                    The Announcer undertakes to propose in the Advert only available assets at its disposal. In case of 
                    unavailability of the property, the Advertiser agrees to proceed with the withdrawal of the Service 
                    Announcement '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' as soon as possible.
                    <br/><br/>
                </li>
                <li>
                    As such, the Advertiser acknowledges and agrees that for technical reasons, the posting of an Advert
                     on the Website and Universal iPhone / iPad and Android applications will not be instantaneous with 
                     its validation.
                    <br/><br/>
                </li>
                <li>
                    The Advertiser declares to be aware of the scope of the Website, to have taken all precautions to 
                    comply with the legislation in force at the place of reception and to unload 
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' of all responsibilities in this regard.
                    <br/><br/>
                </li>
                <li>
                    The Advertiser agrees that the data collected or collected on the Website shall be retained by the 
                    access providers and used for statistical purposes or to respond to specific requests or requests 
                    from public authorities.
                    <br/><br/>
                </li>
                <li>
                    To be admissible, any complaint must indicate precisely the alleged defect(s) of the Advert and be 
                    transmitted in writing to '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' within eight (8) working days of 
                    the filing date.
                    <br/><br/>
                </li>
                <li>
                    The Advertiser declares to be informed that it will, to access the Service 
                    '. env ('LEGAL_COMPAGNY_PSEUDONAME'). ' have access to the Internet underwritten from the supplier 
                    of its choice, the cost of which is borne by it, and acknowledges that:
                    <ul>
                        <li>The reliability of the transmissions is uncertain, in particular because of the 
                            heterogeneous nature of the infrastructures and networks on which they circulate and, in 
                            particular, breakdowns or saturations may occur.
                        </li>
                        <li>It is the Advertiser\'s responsibility to take whatever action it deems appropriate to 
                            ensure the safety of its equipment and its own data, software or other, in particular 
                            against the contamination by any virus and / or attempted intrusion which it could to be a 
                            victim.
                        </li>
                        <li>All equipment connected to the Website is and remains under the sole responsibility of the 
                            Advertiser, the responsibility of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' can not be 
                            searched for any direct or indirect damage that may occur as a result of their connection 
                            to the Website.
                        </li>
                    </ul>
                    <br/>The Advertiser further agrees to respect and maintain the confidentiality of the Login 
                    Identifiers to his Pro Account and expressly acknowledges that any connection to his Pro 
                    Account and any data transmission on or from his Pro Account will be deemed to have been 
                    Carried out by the Advertiser.
                    <br/><br/>Any loss, misappropriation or use of the Connection Identifiers and their possible 
                    consequences are the sole responsibility of the Advertiser.
                </li>
            </ol>',

        'propertyIntellect' => '
            <h2  class="ui dividing header">Article 6: Intellectual Property</h2>
            <ol>
                <li>
                    All intellectual property rights (such as copyright, neighboring rights, trademark rights, rights 
                    of database producers) relating to both the structure and the contents of the Website and the 
                    Universal Applications iPhone / iPad And in particular images, sounds, videos, photographs, logos, 
                    marks, graphic, textual, visual, tools, software, documents, data, etc. (Hereinafter referred to as 
                    "Elements") are reserved. These Elements are the property of '. env('LEGAL_COMPAGNY_PSEUDONAME'). '.
                     These Elements are made available to Advertisers, free of charge, for the sole use of the Service 
                     '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' and in the normal use of its functionalities. The 
                     Advertisers agree not to modify the Elements in any way.
                    <br/><br/>Any unauthorized use of the Website and Universal Applications iPhone / iPad and Android 
                    Components results in infringement of intellectual property rights and constitutes an infringement. 
                    It may also result in a violation of the rights to the image, rights of persons or any other rights 
                    and regulations in force. It may therefore incur civil and / or criminal liability for its author.
                    <br/><br/>
                </li>
                <li>
                    Any Advertiser is prohibited from copying, modifying, creating a derivative work, reversing the 
                    design or assembly or in any other way attempting to locate the source code, sell, assign, license 
                    or otherwise transfer any Right relating to the Elements.
                    <br/><br/>Any Advertiser of the Service'. env('LEGAL_COMPAGNY_PSEUDONAME').'
                    undertakes in particular not to:
                    <ul>
                        <li>Use the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' on behalf of or for the benefit of others.</li>
                        <li>Reproduce in number, for commercial or non-commercial purposes, information or Adverts on 
                            the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' and on Web Site and Universal Applications 
                            iPhone / iPad and Android.
                        </li>
                        <li>Integrating all or part of the content of the Website and the Universal Application iPhone 
                            / iPad and Android into a third party site, for commercial or non-commercial purposes.
                        </li>
                        <li>Use a robot, such as a spider, a web search or retrieval application, or any other means of 
                            recovering or indexing all or part of the content of the Website and the Universal iPhone / iPad
                             Applications and Android, except in the case of express prior authorization 
                             '. env('LEGAL_COMPAGNY_PSEUDONAME'). '.
                        </li>
                        <li>Copy the information on media of any kind allowing to reconstitute all or part of the 
                            original files.
                        </li>
                    </ul>
                    <br/>Any reproduction, representation, publication, transmission, use or modification, extraction, 
                    of all or part of the Elements and in any way whatsoever, made without the prior written permission 
                    of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is unlawful. These unlawful acts are the responsibility 
                    of the perpetrators and may lead to legal proceedings against them, in particular for infringement.
                    <br/><br/>
                </li>
                <li>
                    The marks and logos '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' thus the brands and logos of the partners
                     of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' are registered trademarks. Any total or partial 
                     reproduction of these marks and / or logos without the prior written permission of 
                     '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' Is prohibited.
                    <br/><br/>
                </li>
                <li>
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is the producer of the databases of the Service 
                    '. env ('LEGAL_COMPAGNY_PSEUDONAME'). '. consequently, any extraction and / or re-use of the 
                    database (s) within the meaning of Articles L 342-1 and L 342-2 of the Intellectual Property Code 
                    is prohibited.
                    <br/><br/>
                </li>
                <li>
                    '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' reserves the right to seize all legal remedies against 
                    persons who have not complied with the prohibitions contained in this article.
                    <br/><br/>
                </li>
            </ol>',

        'responsabilityDestock' => '
            <h2 class="ui dividing header">Article 7: Liability and guarantees of '. env('LEGAL_COMPAGNY_PSEUDONAME').'</h2>
            <h3 class="ui header">Disclaimer of liability for '. env('LEGAL_COMPAGNY_PSEUDONAME').'</h3>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' undertakes to use all means necessary to ensure the best possible
                 delivery of the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' which it proposes to the Advertiser 
                 within the framework of an obligation of means. Unless otherwise expressly stated, the service 
                 marketed by '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is limited to the distribution of Announcement, 
                 with subscription of options, on the Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', with the exclusion
                  of any other services. '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' does not guarantee the possible results 
                  anticipated by the Advertiser following the diffusion of the Announcements.
            </p>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' can not be held responsible for the capture of the data that 
                would be made without his knowledge or the traceability that would result.
            </p>
            <p>
                The Website contains a number of hyperlinks to other sites, set up with the permission of 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). '. However, ' . env('LEGAL_COMPAGNY_PSEUDONAME'). ' does not have 
                the possibility to verify the content of the sites thus visited, and will consequently assume no 
                responsibility for this fact.
            </p>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' shall not be liable for any interruptions or alterations of the 
                Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' and / or the Universal iPhone / iPad and Android Web Site
                 or Applications, and the loss of data or information stored by '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' 
                 or by the Advertiser on his Pro Account; It is the Advertiser\'s responsibility to take all necessary 
                 precautions to keep the ads they publish on the Website.
            </p>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' shall not be liable for any unauthorized use of the Service 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' by the Advertiser, and in case of non conformity of the Service 
                with the needs or the specific expectations of the Advertiser.
            </p>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' shall not be held liable, in particular for any direct or 
                indirect damage or damage of any kind whatsoever resulting from the management, use, operation, 
                interruption or malfunction of the Website And Universal Applications iPhone / iPad and Android and / 
                or Service '. env('LEGAL_COMPAGNY_PSEUDONAME'). '.
            </p>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', its subcontractors and suppliers, shall not be liable for any 
                delay or impossibility of fulfilling their contractual obligations in the event of:
            </p>
            <ul>
                <li>Of force majeure,</li>
                <li>Interruption of the connection to the Website due to maintenance or updating of the published 
                    information.
                </li>
                <li>Temporary lack of access to the Website due to technical problems, whatever their origin.
                </li>
                <li>Attack or hacking, deprivation, suppression or prohibition, temporary or permanent, and for any 
                    reason whatsoever, access to the Internet.
                </li>
            </ul>
            <p>
                Furthermore, the Advertiser acknowledges that, in the present state of the art and in the absence of a 
                guarantee of the telecommunications operators, the permanent availability of the Service 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' And in particular the Website can not be guaranteed.
            </p>
            <p>
                Taking into account the technical requirements related to the architecture of the Website, the 
                indications of location and the statistics of the visits and the pages seen by the visitors of the 
                Website are given as an indication. The latter may not result in any recourse or claim of any kind 
                whatsoever on the part of the Advertiser.
            </p>
            <p>
                Except for fraud or gross negligence, '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', its subcontractors and 
                suppliers shall in no case be liable to compensation, pecuniary or in kind, due to errors or omissions 
                in the composition of an Advertisement. In particular, such events shall not in any case justify a 
                refusal of payment, even partial, or entitle to an Announcement at the expense of 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ', or to compensation.
            </p>
            <p>
                It is expressly provided that, in the event that one of the Services 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' provided by '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' to the 
                Advertiser would not succeed, the responsibility of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' herein is 
                limited to the re-delivery of the service in question and which has not been completed.
            </p>
            <p>
                In any event, the responsibility of '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' in respect of any damages 
                whatsoever shall be limited to an amount not exceeding the amounts excluding taxes actually paid by the 
                Advertiser to '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' during the TWO (2) months preceding the event 
                giving rise to the liability '. env('LEGAL_COMPAGNY_PSEUDONAME'). '. in addition, the responsibility of
                ' . env('LEGAL_COMPAGNY_PSEUDONAME'). ' shall only be incurred for the direct damage suffered by the 
                Advertiser resulting from a breach of its contractual obligations as defined herein. The Advertiser 
                therefore waives the right to claim compensation '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' in any capacity 
                whatsoever, for indirect damages such as loss of profit, loss of opportunity, commercial or financial 
                loss, increase in overheads or losses resulting from or resulting from performance Of this Agreement.
            </p>
            <h3 class="ui header">Guarantee of '. env('LEGAL_COMPAGNY_PSEUDONAME').'</h3>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' is committed to ensuring the permanence, continuity and quality 
                of access to and use of the Website and the Pro Account (advertisers).
            </p>
            <p>
                In this respect, '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' will make its best efforts to maintain access 
                to its Website with a guarantee accessibility of 99% of the time over a year except in case of force 
                majeure. If necessary, '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' reserves the right to limit or suspend 
                access to the Website and / or Pro Account for any maintenance and / or improvement. To minimize the 
                impact of the latter, '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' will endeavor to plan it between 8 pm and 
                7 am except in cases of imperative necessity. "
            </p>
            <p>
                The Advertiser expressly acknowledges that this warranty does not cover any failure or interruption of 
                the service caused by the telecom operators and / or the company hosting the Website.
            </p>
            <p>
                It is expressly agreed between the parties that the obligations assumed by the 
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' within the framework of this article are means.
            </p>
            <h3 class="ui header">Subcontracting</h3>
            <p>'. env('LEGAL_COMPAGNY_PSEUDONAME'). ' may freely use the service providers and / or subcontractors of 
                his choice for the realization of all or part of the services, without having to inform the Advertiser or 
                to request its agreement on the identity of these third parties.
            </p>',

        'cookies' => '
            <h2  class="ui dividing header">Article 8: Cookies</h2>
            <p>
                Browsing the Website is likely to cause the installation of cookie(s) on the user\'s computer. A cookie 
                is a small file, which does not allow user identification but records information about a computer\'s 
                navigation on a site. The data thus obtained are intended to facilitate the subsequent navigation on 
                the site, and are also intended to permit various measures of attendance.
            </p>
            <p>
                Refusal to install a cookie may result in the inability to access certain services. The user can however
                 configure his computer as follows, to refuse the installation of cookies:
            </p>
            <ul>
                <li>Under Internet Explorer: tool tab (pictogram in the form of a cog in the top right) / internet options. Click Confidentiality and choose Block All Cookies. Confirm with OK.</li>
                <li>Under Firefox: At the top of the browser window, click the Firefox button, then go to the Options tab. Click on the Privacy tab. Set the Retention rules to: use the custom settings for the history. Finally uncheck it to disable cookies.</li>
                <li>Under Safari: Click at the top right of the browser on the menu pictogram (symbolized by a cog). Select Settings. Click Show Advanced Settings. In the "Privacy" section, click Content Settings. In the "Cookies" section, you can block cookies.</li>
                <li>Under Chrome: Click on the upper right of the browser on the menu pictogram (symbolized by three horizontal lines). Select Settings. Click Show Advanced Settings. In the "Privacy" section, click Preferences. In the "Privacy" tab, you can block cookies.</li>
            </ul>',

        'modification' => '
            <h2  class="ui dividing header">Article 9: Modification of the service '. env('LEGAL_COMPAGNY_PSEUDONAME').'</h2>
            <p>
                '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' reserves the right at any time to modify or interrupt the 
                accessibility of all or part of its service and / or the Website or the Applications.
            </p>',

        'juridiction' => '
            <h2  class="ui dividing header">Article 10: Jurisdiction and Miscellaneous Provisions</h2>
            <p>
                Any litigation falls within the exclusive jurisdiction of the Commercial Court of Tours (37000) in 
                France, even in the event of a warranty call or multiple defendants, or in the case of urgent or 
                conservatory proceedings, request. These <a href="'. route('cgu').'">general conditions of use</a> 
                and <a href="'. route('cgv').'">general conditions of Sale</a> are subject to French law.
            </p>
            <p>
                If any of the <a href="'. route('cgu').'">Terms & Conditions</a> were to be found to be 
                unlawful, invalid or unenforceable for any reason whatsoever, the Would be deemed unwritten, without 
                affecting the validity of the remaining provisions which will continue to apply between the Advertisers 
                and '. env('LEGAL_COMPAGNY_PSEUDONAME'). '.
            </p>',

    ]

];
