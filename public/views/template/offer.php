
<div class="offer">
    <div class="mobile-upper-part">
        <div class="offer-image">
            <img src="public/uploads/<?= $offer->getAircraft()->getImage(0);?>">
        </div>
    <div class="offer-info-container">
        <div class="offer-title">
            <b><?= $offer->getTitle(); ?></b>
        </div>
        <div class="offer-data">
            <div class="offer-location-description">
                <div class="offer-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <b><?= $offer->getAddressString(); ?></b>
                </div>
                <div class="offer-description">
                    <b><?= $offer->getDescription(); ?></b>
                </div>
            </div>
            <div class="offer-info">
                <div class="up">
                    <div class="offer-price offer-info-border">
                        <b>Price <?= $offer->getPrice(); ?>$</b>
                    </div>
                    <div class="offer-license offer-info-border">
                        Licenses <?php $offer->getAircraft() ->printLicenseTypes(); ?>
                    </div>
                </div>
                <div class="offer-category bottom offer-info-border">
                    Category <?= $offer->getAircraft()->getCategory();?>
                </div>
            </div>
            <div class="buttons">
                <a href="offer_details?id=<?=$offer->getId()?>" class="offer-button">View details</a>
                <?php if (isset($_SESSION['admin']) || (isset($owner) && $owner === true)) {?>
                    <form class="" action="removeOffer" method="POST">
                    <button class="offer-button" type="submit"> Remove offer </button>
                    <input type="hidden" name="id" id="id" value="<?=$offer->getId()?>">
                </form>
                <?php }
                ?>
            </div>
        </div>
    </div>
    </div>

    <div class="mobile-buttons">
        <a href="offer_details?id=<?=$offer->getId()?>" class="offer-button">View details</a>
    </div>

</div>

