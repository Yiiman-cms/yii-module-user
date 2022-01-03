<?php
	/**
	 * Created by YiiMan TM.
	 * Programmer: gholamreza beheshtian
	 * Mobile:09353466620
	 * Company Phone:05138846411
	 * Site:https://yiiman.ir
	 * Date: 1/28/2019
	 * Time: 8:08 AM
	 */


?>
<style>
	.field-label {
		margin-top: 40px;
		color: hsla(0,0%,92%,.5);
		text-transform: uppercase;
	}
</style>
<div class="main">
	<div class="container-tab-contact">
		<div class="col-1-tab-contact"><h1><span
						class="subtitle">ثبت نام در توکاپس</span>
			</h1>ثبت نام در توکاپس</div>
		<h2 class="small-heading stroke-text contact-1"
		    data-ix="title-tabs-2">
		</h2>
		<div class="form-block w-form">
			<form id="wf-form-Briefing---Projet" name="wf-form-Briefing---Projet" data-name="فرم ثبت نام" method="post" autocomplete="off" class="form">
				<div class="fieldset">
					<h3
							class="heading-form"></h3>
					<label
							for="Nom"
							class="field-label"></label><input
							type="text" class="text-field w-input" maxlength="256" name="Nom"
							data-name="Nom" id="Nom" required="" /><label for="Pr-nom"
				                                                          class="field-label">شماره تلفن همراه
						*</label><input
							type="text" class="text-field w-input" maxlength="256" name="Pr-nom"
							data-name="Prénom" id="Prenom" required="" /><label for="Email-2"
				                                                                class="field-label"><?= $sec_contact['section2']['tab1']['label-email'] ?>
						*</label><input
							type="email" class="text-field w-input" maxlength="256" name="Email"
							data-name="Email" id="Email" required="" /><label for="T-l-phone"
				                                                              class="field-label"><?= $sec_contact['section2']['tab1']['label-tel'] ?></label><input
							type="text" class="text-field w-input" maxlength="256"
							name="T-l-phone" data-name="Téléphone" id="Telephone" /><label
							for="Entreprise"
							class="field-label"><?= $sec_contact['section2']['tab1']['label-entreprise'] ?></label><input
							type="text" class="text-field w-input" maxlength="256"
							name="Entreprise" data-name="Entreprise" id="Entreprise" /><label
							for="Site-web-2"
							class="field-label"><?= $sec_contact['section2']['tab1']['label-url'] ?></label><input
							type="text" class="text-field w-input" maxlength="256"
							name="Site-web" data-name="Site web" id="Site-web" /></div>
				<div class="fieldset"><h3 class="heading-form"><?= $sec_contact['section2']['tab1']['dep-title'] ?></h3>
					<div class="row-field w-row">
						<div class="w-col w-col-6">
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="Logo" name="Logo"
							                                              data-name="Logo"
							                                              checked=""
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="Logo"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-1'] ?></label>
							</div>
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="Identité visuelle"
							                                              name="Identit-visuelle"
							                                              data-name="Identité visuelle"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="Identité visuelle"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-2'] ?></label>
							</div>
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="UX/UI"
							                                              name="UX-UI"
							                                              data-name="UX/UI"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="UX/UI"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-3'] ?></label>
							</div>
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="e-shop"
							                                              name="e-shop"
							                                              data-name="e-shop"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="e-shop"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-4'] ?></label>
							</div>
						</div>
						<div class="col-right w-col w-col-6">
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="Siteweb"
							                                              name="Siteweb"
							                                              data-name="Siteweb"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="Siteweb"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-5'] ?></label>
							</div>
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="Photo"
							                                              name="Photo"
							                                              data-name="Photo"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="Photo"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-6'] ?></label>
							</div>
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="Vidéo"
							                                              name="Vid-o"
							                                              data-name="Vidéo"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="Vidéo"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-7'] ?></label>
							</div>
							<div class="checkbox-field w-checkbox"><input type="checkbox"
							                                              id="Autre"
							                                              name="Autre"
							                                              data-name="Autre"
							                                              class="checkbox display-none w-checkbox-input" /><label
										for="Autre"
										class="checkbox-label w-form-label"><?= $sec_contact['section2']['tab1']['dep-7'] ?></label>
							</div>
						</div>
					</div>
				</div>
				<div class="fieldset"><h3 class="heading-form"><?= $sec_contact['section2']['tab1']['vot-title'] ?></h3>
					<div class="row-field w-row">
						<div class="w-col w-col-6">
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="1000-10."
							                                               name="Budget"
							                                               value="1000-10.000"
							                                               data-name="Budget"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="1000-10."
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['vot-1'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="10.000-20."
							                                               name="Budget"
							                                               value="10.000-20.000"
							                                               data-name="Budget"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="10.000-20."
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['vot-2'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="20.000-50."
							                                               name="Budget"
							                                               value="20.000-50.000"
							                                               data-name="Budget"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="20.000-50."
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['vot-3'] ?></label>
							</div>
						</div>
						<div class="col-right w-col w-col-6">
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="Pas de limite"
							                                               name="Budget"
							                                               value="Pas de limite"
							                                               data-name="Budget"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="Pas de limite"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['vot-4'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="Je ne sais pas"
							                                               name="Budget"
							                                               value="Je ne sais pas"
							                                               data-name="Budget"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="Je ne sais pas"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['vot-5'] ?></label></label>
							</div>
						</div>
					</div>
				</div>
				<div class="fieldset"><h3 class="heading-form"><?= $sec_contact['section2']['tab1']['not-title'] ?></h3>
					<div class="row-field w-row">
						<div class="w-col w-col-6">
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="En urgence"
							                                               name="Deadline"
							                                               value="En urgence"
							                                               data-name="Deadline"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="En urgence"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['not-1'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="1 mois"
							                                               name="Deadline"
							                                               value="1 mois"
							                                               data-name="Deadline"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="1 mois"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['not-2'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="3 mois"
							                                               name="Deadline"
							                                               value="3 mois"
							                                               data-name="Deadline"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="3 mois"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['not-3'] ?></label>
							</div>
						</div>
						<div class="col-right w-col w-col-6">
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="6 mois"
							                                               name="Deadline"
							                                               value="6 mois"
							                                               data-name="Deadline"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="6 mois"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['not-4'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="1 an"
							                                               name="Deadline"
							                                               value="1 an"
							                                               data-name="Deadline"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="1 an"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['not-5'] ?></label>
							</div>
							<div class="radio-button-field w-radio"><input type="radio"
							                                               id="+ de 1 an"
							                                               name="Deadline"
							                                               value="+ de 1 an"
							                                               data-name="Deadline"
							                                               required=""
							                                               class="radio-button w-radio-input" /><label
										for="+ de 1 an"
										class="radio-button-label w-form-label"><?= $sec_contact['section2']['tab1']['not-6'] ?></label>
							</div>
						</div>
					</div>
				</div>
				<div class="fieldset"><h3 class="heading-form"><?= $sec_contact['section2']['tab1']['rot-title'] ?></h3>
					<textarea id="Message" name="Message"
					          placeholder="<?= $sec_contact['section2']['tab1']['rot-1'] ?> "
					          maxlength="5000" data-name="Message"
					          class="textarea w-input"></textarea></div>
				<div class="fieldset submit">
					<div class="radio-button-field w-radio"><input type="radio" id="Confirmed"
					                                               name="Confirm-RGPD"
					                                               value="Confirmed"
					                                               data-name="Confirm RGPD"
					                                               required=""
					                                               class="radio-button w-radio-input" /><label
								for="Confirmed" class="radio-button-label confirm w-form-label"> <a
									href="mentions-legales"
									target="_blank"><?= $sec_contact['section2']['tab1']['rot-2'] ?> </a></label>
					</div>
					<div class="container-recaptcha">
						<div data-theme="dark"
						     data-sitekey="6LeMZmEUAAAAAJrCDPpm0KEpyvXVmYSHo95LMqjB"
						     class="w-form-formrecaptcha g-recaptcha g-recaptcha-error g-recaptcha-disabled"></div>
					</div>
					<input type="submit" value="<?= $sec_contact['section2']['tab3']['send'] ?>"
					       data-wait="Please wait..."
					       class="submit-button link w-button" /></div>
			</form>
			<div class="success-message w-form-done">
				<div class="container-success-message"><h2
							class="small-heading stroke-text succes-message"><?= $sec_contact['section2']['tab1']['rot-3'] ?></h2>
					<img
							src="<?= $sec_contact['section2']['img-check'] ?>"
							width="30" />
					<div class="succes-message"><?= $sec_contact['section2']['tab1']['rot-4'] ?> 
					</div>
				</div>
			</div>
			<div class="error-message-2 w-form-fail">
				<div class="container-error-message">
					<div class="bloc-error">
						<div><?= $sec_contact['section2']['tab1']['rot-5'] ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

