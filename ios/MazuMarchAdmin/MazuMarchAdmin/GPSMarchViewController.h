//
//  GPSMarchViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Header.h"
#import "AFHTTPRequestOperationManager.h"
@import GoogleMaps;


@interface GPSMarchViewController : UIViewController

@property NSString *marchId;
@property UIScrollView *scrollView;
@property UIImageView *batteryImage;
@property GMSMarker *mazu;

@property UILabel *batteryTitleLabel;
@property UILabel *lastTimeTitleLabel;
@property UILabel *latTitleLabel;
@property UILabel *lngTitleLabel;
@property UILabel *accuracyTitleLabel;

@property UILabel *batteryLabel;
@property UILabel *lastTimeLabel;
@property UILabel *latLabel;
@property UILabel *lngLabel;
@property UILabel *accuracyLabel;


@property GMSMapView *mapView;


-(GPSMarchViewController *)initWithId:(NSString *)i;

@end
