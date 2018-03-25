<?php

return [

    'title' => 'Privacy and cookies',
    'explanations' => '
            <h2  class="ui dividing header">Privacy</h2>
            <p>
                In accordance with the General Regulations on Data Protection (European RGPD), this page aims to list 
                the information we collect, why and how we use it. <br />
                To use our services, you will have to agree that our company will collect some of your personal informations.
            </p>',

    'CollectedList' => '
        <h3  class="ui dividing header">What data is collected</h3>
            <ol>
              <li>
                The data you fill in
                <ul>
                  <li>
                    During a simple visit <br />
                    We do not ask you to fill in any data.
                  </li>
                  <li>
                    When subscribing to the newsletter <br />
                    We collect your email address. Optionally you can enter your name and a phone number.
                  </li>
                  <li>
                    When creating a professional account <br />
                    To create an account you must fill in your email, a username and a password.
                    If you agree, a geographical position is recorded for the purpose of
                    subsequent billing. In the opposite case, it is deduced from the data collected automatically.
                  </li>
                  <li>
                    Additional information from the professional account <br />
                    When creating an ad you are asked to fill in your VAT number
                    Intra-Community in order to establish a valid invoice. We then automatically collect
                    the name of your company and its address (replacing the address initially
                    obtained when creating the account). You can optionally enter a number of
                    phone in order to make it available in your ads.
                  </li>
                </ul>
              </li>
              <li>
                Data collected automatically when using our services <br />
                We collect the following information about the services you use and the use that you do it: <br />
                Technical data: IP address, internet connection, browser type, information about your computer <br />
                Data collected using cookies: for more information, we invite you to go to COOKIES section
              </li>
              <li>
                Data collected by third parties <br />
                Our payment gateway <a href="https://www.braintreepayments.com/"> Braintree </a> has its own
                privacy policy available <a href="https://www.paypal.com/webapps/mpp/ua/privacy-full"> here </a> <br />
                Our statistics provider visits <a href="https://analytics.google.com//"> Google analytics </a>
                has its own privacy policy available
                <a href="https://www.google.com/intl/en/policies/privacy/"> here </a> <br />
                An exit or a redirection out of our site, excludes the application of the present Policy of
                Confidentiality.
              </li>
            </ol>',

    'Uses' => '
        <h3  class="ui dividing header">What is the usage of the collected data</h3>
            <ol>
              <li>
                Provide our services <br />
                To ensure you access to our services and their use, including:
                <ul>
                  <li> ensure the publication of your ad </li>
                  <li> manage your account </li>
                  <li> geolocate your property </li>
                  <li> answer your questions </li>
                  <li> forward messages and private content exchanged via the Messaging. </li>
                </ul>
                In accordance with article 68 of the law for a digital Republic, messages and contents exchanged
                between Users and Advertisers via the Messaging are private correspondence and are confidential.
              </li>
              <li>
                Do some marketing analysis <br />
                Through anonymous syntheses of data to understand through dashboards
                how our users use our services in order to improve them.
              </li>
            </ol>',

    'Destinations' => '
        <h3  class="ui dividing header">Recipients of personal data collected</h3>
            <p>In accordance with the law n ° 78-17 of January 6th, 1978 modified by the law n ° 2004-801 of August 6th, 2004, 
            '. env ("LEGAL_COMPAGNY_PSEUDONAME"). ' undertakes to keep all personal data collected via its service and 
            not to pass it on to any third party. <br />
            By way of derogation, the User and the Advertiser are informed that '. env ("LEGAL_COMPAGNY_PSEUDONAME"). ' 
            may be required to communicate the personal data collected via its service:</p>
            <ul>
              <li> Authorized administrative and judicial authorities, only on judicial requisition </li>
              <li> To technical service providers who help us provide and improve service </li>
              <li> Has technical service providers to offer you services and offers adapted to
              your interests (<a href="https://www.google.com/settings/u/0/ads"> Google Customer Match </a>) </li>
            </ul>',

    'manage' => '
        <h3  class="ui dividing header">Management and deletion of my personal data</h3>
            <ol>
              <li>
                Access and Modification <br />
                You may, at any time, access and modify the personal data of your Account. There you
                just go to your account. If a VAT number is entered, only a VAT number valid can replace the existing.
              </li>
              <li>
                Supression <br />
                In accordance with Articles 38, 39 and 40 of Law No 78-17 of 6 January 1978 as amended by Law 
                No 2004-801 of 6 August 2004, any natural person shall at all times have the right of access, 
                rectification, deletion and opposition to the processing of data concerning him.
 
                The User and the Advertiser may exercise this right by contacting '. env ("LEGAL_COMPAGNY_PSEUDONAME"). ' 
                via the "contact" section.
              </li>
            </ol>',

    'conservation' => '
        <h3  class="ui dividing header">Durée de conservation</h3>
            <p>
            The duration of the conversation
            </p>
            <ul>
              <li> The data relating to the management of your account are kept for a period of 5 years from 
              of its deletion for the sole purpose of proof </li>
              <li> The documents and accounting documents are kept for 10 years, as accounting proof </li>
              <li> Transaction numbers for a period of 5 years from the date of payment </li>
              <li> The login data is kept for 15 days from collection. </li>
            </ul>',

    'security' => '
        <h3  class="ui dividing header">Data security</h3>
            <p>
            We protect your information with physical, electronic and administrative security measures. 
            Our safeguards include firewalls and access authorization checks.
            </p>
            <p>
            Responsible for treatment, declaration contact: <br />
            '. env ("LEGAL_COMPAGNY_PSEUDONAME"). ', represented by Mr Sylvain Gagnot, in his capacity as 
            Managing Director, is responsible for the processing of the data that it collects on its service.
            </p>
            <p>
            In accordance with the law nº78-17 of January 6th, 1978, known as "Data processing and freedoms", 
            modified by the Law No 2004-801 of 6 August 2004, '. env ("LEGAL_COMPAGNY_PSEUDONAME"). ' has been the 
            subject of National Commission for Computing and Freedoms (C.N.I.L), simplified declaration no. ' . env("LEGAL_CNIL_NUMBER") . '
            </p>',

    'cookies' => '
            <h2  class="ui dividing header">Cookies</h2>
            <p>
                The navigation on the Website is likely to cause the installation of
                cookie (s) on the user\'s computer. A cookie is a small file, 
                which does not allow the identification of the user, but which records 
                information about navigating a computer on a site. The data as well
                obtained are intended to facilitate subsequent navigation on the site, and have also been
                vocation to allow various measures of attendance.
            </p>
            <p>
                The refusal to install a cookie may result in the impossibility of accessing certain
                services. However, the user can configure his computer as follows, to refuse the installation of cookies:
            </p>
            <ul>
                <li> Under Internet Explorer: tool tab (pictogram in form of cog in top on the right) / internet options. Click Privacy and choose Block all cookies. Validate on Ok. </li>
                <li> In Firefox: at the top of the browser window, click the Firefox button, then go to the Options tab. Click on the Privacy tab. Set the retention rules to: use custom settings for history. Finally uncheck it to disable cookies. </li>
                <li> Under Safari: Click on the top right of the browser on the menu icon (symbolized by a cog). Select Settings. Click Show Advanced Settings. In the "Privacy" section, click Content Settings. In the "Cookies" section, you can block cookies. </li>
                <li> Under Chrome: Click at the top right of the browser on the menu icon (symbolized by three horizontal lines). Select Settings. Click Show Advanced Settings. In the "Privacy" section, click Preferences. In the "Privacy" tab, you can block cookies. </li>
            </ul>
            <p><a href="javascript:tarteaucitron.userInterface.openPanel();">Manage my cookies</a></p>',

];
