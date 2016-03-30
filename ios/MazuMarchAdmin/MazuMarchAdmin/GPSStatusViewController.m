//
//  GPSStatusViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/30.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "GPSStatusViewController.h"

@interface GPSStatusViewController ()

@end

@implementation GPSStatusViewController

+ (CLLocationCoordinate2D) oriLocation {
    return CLLocationCoordinate2DMake(23.567600533837, 120.30456438661);
}
- (void)viewDidLoad {
    [super viewDidLoad];
    
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];
    
    
    
    
    self.scrollView = [UIScrollView new];
    
    [self.scrollView setTranslatesAutoresizingMaskIntoConstraints:NO];
    
    [self.view addSubview:self.scrollView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];
    

    
    
    
    
    
    
    
    self.batteryImage = [UIImageView new];
    [self.batteryImage setTranslatesAutoresizingMaskIntoConstraints:NO];
    
    [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
    [self.batteryImage.layer setBorderWidth:5.0f / [UIScreen mainScreen].scale];
    [self.batteryImage.layer setCornerRadius:60];
    [self.batteryImage setClipsToBounds:YES];
    
    
    
    [self.scrollView addSubview: self.batteryImage];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeTop multiplier:1 constant:20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeCenterX multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:120]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:120]];
    
    self.batteryTitleLabel = [UILabel new];
    [self.batteryTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.batteryTitleLabel setText:@"電池電量："];
    [self.batteryTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.batteryTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.batteryImage attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.batteryImage attribute:NSLayoutAttributeCenterX multiplier:1 constant:-1]];

    
    
    
    self.batteryLabel = [UILabel new];
    [self.batteryLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.batteryLabel setText:@""];
    
    [self.scrollView addSubview: self.batteryLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.batteryTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.batteryTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    
    self.lastTimeTitleLabel = [UILabel new];
    [self.lastTimeTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.lastTimeTitleLabel setText:@"上次更新："];
    [self.lastTimeTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.lastTimeTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.batteryTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.batteryTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.lastTimeLabel = [UILabel new];
    [self.lastTimeLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.lastTimeLabel setText:@""];
    
    [self.scrollView addSubview: self.lastTimeLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    
    
    self.latTitleLabel = [UILabel new];
    [self.latTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.latTitleLabel setText:@"緯度座標："];
    [self.latTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.latTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.latLabel = [UILabel new];
    [self.latLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.latLabel setText:@""];
    
    [self.scrollView addSubview: self.latLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.latTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.latTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];

    
    self.lngTitleLabel = [UILabel new];
    [self.lngTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.lngTitleLabel setText:@"經度座標："];
    [self.lngTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.lngTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lngTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.latTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lngTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.latTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.lngLabel = [UILabel new];
    [self.lngLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.lngLabel setText:@""];
    
    [self.scrollView addSubview: self.lngLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lngLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.lngTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lngLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.lngTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    
    self.accuracyTitleLabel = [UILabel new];
    [self.accuracyTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.accuracyTitleLabel setText:@"水平準度："];
    [self.accuracyTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.accuracyTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.lngTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.lngTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.accuracyLabel = [UILabel new];
    [self.accuracyLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.accuracyLabel setText:@""];
    
    [self.scrollView addSubview: self.accuracyLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.accuracyTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.accuracyTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    
    
    self.mapView = [GMSMapView mapWithFrame:CGRectZero camera:[GMSCameraPosition cameraWithLatitude:[GPSStatusViewController oriLocation].latitude
                                                                                          longitude:[GPSStatusViewController oriLocation].longitude
                                                                                               zoom:16]];
    [self.mapView setAccessibilityElementsHidden:NO];
    [self.mapView setMyLocationEnabled:YES];
    [self.mapView.settings setMyLocationButton:YES];
    [self.mapView setPadding:UIEdgeInsetsMake(0.0, 0.0, 0.0, 0.0)];
    [self.mapView.layer setBorderColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00].CGColor];
    [self.mapView.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    [self.mapView.layer setCornerRadius:2];
    [self.mapView setClipsToBounds:YES];

    
    [self.mapView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.scrollView addSubview:self.mapView];
    
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.accuracyTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeBottom multiplier:1 constant:-10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:-20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:220]];

    self.mazu = [GMSMarker new];
    [self.mazu setIcon:[[UIImage imageNamed:@"mazu"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [self.mazu setMap:self.mapView];
    [self.mazu setPosition:[GPSStatusViewController oriLocation]];
}
- (void)setHidden {
    [self.batteryImage setHidden:YES];
    [self.batteryTitleLabel setHidden:YES];
    [self.lastTimeTitleLabel setHidden:YES];
    [self.latTitleLabel setHidden:YES];
    [self.lngTitleLabel setHidden:YES];
    [self.accuracyTitleLabel setHidden:YES];

    
    [self.batteryLabel setHidden:YES];
    [self.lastTimeLabel setHidden:YES];
    [self.latLabel setHidden:YES];
    [self.lngLabel setHidden:YES];
    [self.accuracyLabel setHidden:YES];
    
    [self.mapView setHidden:YES];
}
- (void)setShow:(NSDictionary *) data {
    [self.batteryImage setHidden:NO];
    [self.batteryTitleLabel setHidden:NO];
    [self.lastTimeTitleLabel setHidden:NO];
    [self.latTitleLabel setHidden:NO];
    [self.lngTitleLabel setHidden:NO];
    [self.accuracyTitleLabel setHidden:NO];
    
    [self.batteryLabel setHidden:NO];
    [self.lastTimeLabel setHidden:NO];
    [self.latLabel setHidden:NO];
    [self.lngLabel setHidden:NO];
    [self.accuracyLabel setHidden:NO];
    
    [self.mapView setHidden:NO];
    
    int i = (int)[[data objectForKey:@"battery"] integerValue] / 25;
    [self.batteryImage setImage:[[UIImage imageNamed:[NSString stringWithFormat:@"battery_%02d", i]] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    
    switch (i) {
        case 0:
            [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.87 green:0.17 blue:0.00 alpha:1.00].CGColor];
            break;
        case 1:
            [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.99 green:0.60 blue:0.16 alpha:1.00].CGColor];
            break;
        case 2:
            [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.56 green:0.79 blue:0.30 alpha:1.00].CGColor];
            break;
        case 3:
            [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.30 green:0.69 blue:0.31 alpha:1.00].CGColor];
            break;
        default:
            break;
    }
    
    [self.batteryLabel setText:[NSString stringWithFormat:@"%@%%", [data objectForKey:@"battery"]]];
    [self.lastTimeLabel setText:[data objectForKey:@"time_at"]];

    [self.latLabel setText:[NSString stringWithFormat:@"%@", [data objectForKey:@"latitude2"]]];
    [self.lngLabel setText:[NSString stringWithFormat:@"%@", [data objectForKey:@"longitude2"]]];
    [self.accuracyLabel setText:[NSString stringWithFormat:@"%@ 公尺", [data objectForKey:@"accuracy_horizontal"]]];
    [self.mapView setCamera:[GMSCameraPosition cameraWithLatitude:[[data objectForKey:@"latitude2"] doubleValue]
                                                        longitude:[[data objectForKey:@"longitude2"] doubleValue]
                                                             zoom:16]];
    [self.mazu setPosition:CLLocationCoordinate2DMake([[data objectForKey:@"latitude2"] doubleValue], [[data objectForKey:@"longitude2"] doubleValue])];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self setHidden];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager GET:[NSString stringWithFormat:LAST_API_URL, (int)[[USER_DEFAULTS objectForKey:@"march_id"] integerValue]]
              parameters:nil
                 success:^(AFHTTPRequestOperation *operation, id responseObject) {
                     [self setShow: [responseObject objectForKey:@"last"]];
                     [alert dismissViewControllerAnimated:YES completion:nil];
                 }
                 failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                     
                     [alert dismissViewControllerAnimated:YES completion:^{
                         UIAlertController *backAlert = [UIAlertController
                                                         alertControllerWithTitle:@"目前沒有任何資料！"
                                                         message:nil
                                                         preferredStyle:UIAlertControllerStyleAlert];
                         
                         [backAlert addAction:[UIAlertAction
                                               actionWithTitle:@"確定"
                                               style:UIAlertActionStyleDefault
                                               handler:^(UIAlertAction * action) {
                                                   [self.navigationController popToRootViewControllerAnimated:YES];
                                               }]];
                         
                         [self.parentViewController presentViewController:backAlert animated:YES completion:nil];
                     }];
                 }
         ];
    }];
    
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
 #pragma mark - Navigation
 
 // In a storyboard-based application, you will often want to do a little preparation before navigation
 - (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
 // Get the new view controller using [segue destinationViewController].
 // Pass the selected object to the new view controller.
 }
 */

@end
