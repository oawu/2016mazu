//
//  GPSMarchViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "GPSMarchViewController.h"

@interface GPSMarchViewController ()

@end

@implementation GPSMarchViewController

-(GPSMarchViewController *)initWithId:(NSString *)i {
    self = [super init];
    if (self) self.marchId = i;
    return self;
}

+ (CLLocationCoordinate2D) oriLocation {
    return CLLocationCoordinate2DMake(23.567600533837, 120.30456438661);
}
- (void)viewDidLoad {
    [super viewDidLoad];

    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    
    
    self.scrollView = [UIScrollView new];
    [self.scrollView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.view addSubview:self.scrollView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];
    
    
    int w = 80;
    int s = 15;
    self.batteryImage = [UIImageView new];
    [self.batteryImage setTranslatesAutoresizingMaskIntoConstraints:NO];
    
    [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
    [self.batteryImage.layer setBorderWidth:5.0f / [UIScreen mainScreen].scale];
    [self.batteryImage.layer setCornerRadius:w / 2];
    [self.batteryImage setClipsToBounds:YES];
    
    
    
    [self.scrollView addSubview: self.batteryImage];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeTop multiplier:1 constant:20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeCenterX multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:w]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryImage attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:w]];
    
    self.batteryTitleLabel = [UILabel new];
    [self.batteryTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.batteryTitleLabel setText:@"電池電量："];
    [self.batteryTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.batteryTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.batteryImage attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.batteryTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:10]];
    
    
    
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
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.batteryTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.batteryTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.lastTimeLabel = [UILabel new];
    [self.lastTimeLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.lastTimeLabel setText:@""];
    
    [self.scrollView addSubview: self.lastTimeLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.lastTimeLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    
    
    self.latLngTitleLabel = [UILabel new];
    [self.latLngTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.latLngTitleLabel setText:@"經緯座標："];
    [self.latLngTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.latLngTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latLngTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latLngTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.lastTimeTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.latLngLabel = [UILabel new];
    [self.latLngLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.latLngLabel setText:@""];
    
    [self.scrollView addSubview: self.latLngLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latLngLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.latLngTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.latLngLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.latLngTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    self.accuracyTitleLabel = [UILabel new];
    [self.accuracyTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.accuracyTitleLabel setText:@"水平準度："];
    [self.accuracyTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.accuracyTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyTitleLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.batteryLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.batteryImage attribute:NSLayoutAttributeCenterX multiplier:1 constant:10]];
    
    
    self.enableTitleLabel = [UILabel new];
    [self.enableTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.enableTitleLabel setText:@"是否啟用："];
    [self.enableTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.enableTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.enableTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.latLngTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.enableTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.latLngTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    self.enableSwitchButton = [UISwitch new];
    [self.enableSwitchButton setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.enableSwitchButton addTarget:self action:@selector(setState:) forControlEvents:UIControlEventValueChanged];
    
    [self.scrollView addSubview: self.enableSwitchButton];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.enableSwitchButton attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.enableTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.enableSwitchButton attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.enableTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    

    
    
    self.distanceTitleLabel = [UILabel new];
    [self.distanceTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.distanceTitleLabel setText:@"觸發距離："];
    [self.distanceTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.distanceTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.distanceTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.enableTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.distanceTitleLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.enableTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:0]];
    
    
    self.stepper = [UIStepper new];
    [self.stepper setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.stepper setMaximumValue:10.0];
    [self.stepper setMinimumValue:0];
    [self.stepper setValue:0];
    [self.stepper addTarget:self action:@selector(stepperChanged:) forControlEvents:UIControlEventValueChanged];
    
    
    [self.scrollView addSubview: self.stepper];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.stepper attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.distanceTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.stepper attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.distanceTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    self.distanceLabel = [UILabel new];
    [self.distanceLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.distanceLabel setText:@"0 公尺"];
    [self.distanceLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.distanceLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.distanceLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.stepper attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.distanceLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.stepper attribute:NSLayoutAttributeRight multiplier:1 constant:10]];
    
    
    self.accuracyLabel = [UILabel new];
    [self.accuracyLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.accuracyLabel setText:@""];
    
    [self.scrollView addSubview: self.accuracyLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.accuracyTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.accuracyLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.accuracyTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    

    self.mapView = [GMSMapView mapWithFrame:CGRectZero camera:[GMSCameraPosition cameraWithLatitude:[GPSMarchViewController oriLocation].latitude
                                                                                          longitude:[GPSMarchViewController oriLocation].longitude
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
    
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.distanceTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeBottom multiplier:1 constant:-10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:10]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:-20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:220]];
    
    self.mazu = [GMSMarker new];
    [self.mazu setIcon:[[UIImage imageNamed:@"mazu"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [self.mazu setMap:self.mapView];
    [self.mazu setPosition:[GPSMarchViewController oriLocation]];
}

- (void)setHidden {
    [self.batteryImage setHidden:YES];
    [self.batteryTitleLabel setHidden:YES];
    [self.lastTimeTitleLabel setHidden:YES];
    [self.latLngTitleLabel setHidden:YES];
    [self.accuracyTitleLabel setHidden:YES];
    [self.enableTitleLabel setHidden:YES];
    [self.distanceTitleLabel setHidden:YES];
    [self.distanceLabel setHidden:YES];
    
    [self.batteryLabel setHidden:YES];
    [self.lastTimeLabel setHidden:YES];
    [self.latLngLabel setHidden:YES];
    [self.accuracyLabel setHidden:YES];
    [self.enableSwitchButton setHidden:YES];
    [self.stepper setHidden:YES];

    [self.mapView setHidden:YES];
}
- (void)setShow:(NSDictionary *) data {
    [self.batteryImage setHidden:NO];
    [self.batteryTitleLabel setHidden:NO];
    [self.lastTimeTitleLabel setHidden:NO];
    [self.latLngTitleLabel setHidden:NO];
    [self.accuracyTitleLabel setHidden:NO];
    [self.enableTitleLabel setHidden:NO];
    [self.distanceTitleLabel setHidden:NO];
    [self.distanceLabel setHidden:NO];
    
    [self.batteryLabel setHidden:NO];
    [self.lastTimeLabel setHidden:NO];
    [self.latLngLabel setHidden:NO];
    [self.accuracyLabel setHidden:NO];
    [self.enableSwitchButton setHidden:NO];
    [self.stepper setHidden:NO];
    
    [self.mapView setHidden:NO];
    
    NSDictionary *last = [data objectForKey:@"last"];
    int i = (int)[[last objectForKey:@"battery"] integerValue] / 25;
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
        default:
        case 3:
            [self.batteryImage.layer setBorderColor:[UIColor colorWithRed:0.30 green:0.69 blue:0.31 alpha:1.00].CGColor];
            break;
    }

    [self.batteryLabel setText:[NSString stringWithFormat:@"%@%%", [last objectForKey:@"battery"]]];
    [self.lastTimeLabel setText:[last objectForKey:@"time_at"]];
    
    [self.latLngLabel setText:[NSString stringWithFormat:@"%@, %@", [last objectForKey:@"latitude2"], [last objectForKey:@"longitude2"]]];
    [self.accuracyLabel setText:[NSString stringWithFormat:@"%@ 公尺", [last objectForKey:@"accuracy_horizontal"]]];
    [self.mapView setCamera:[GMSCameraPosition cameraWithLatitude:[[last objectForKey:@"latitude2"] doubleValue]
                                                        longitude:[[last objectForKey:@"longitude2"] doubleValue]
                                                             zoom:16]];
    [self.mazu setPosition:CLLocationCoordinate2DMake([[last objectForKey:@"latitude2"] doubleValue], [[last objectForKey:@"longitude2"] doubleValue])];
    

     NSDictionary *march = [data objectForKey:@"march"];

    [self.enableSwitchButton setOn:[[march objectForKey:@"is_enabled"] boolValue] animated:NO];
    [self.stepper setValue:(int)[[march objectForKey:@"distance"] integerValue]];
    [self.distanceLabel setText:[NSString stringWithFormat:@"%@ 公尺", [march objectForKey:@"distance"]]];
}

- (void)stepperChanged:(UIStepper*)sender {
    int distance = (int)[sender value];

    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"更新中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        NSMutableDictionary *data = [NSMutableDictionary new];
        [data setValue:[NSString stringWithFormat:@"%d", distance] forKey:@"distance"];
        [data setValue:@"put" forKey:@"_method"];
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager POST:[NSString stringWithFormat:PUT_MARCH_API_URL, self.marchId]
               parameters:data
                  success:^(AFHTTPRequestOperation *operation, id responseObject) {
                      [self.distanceLabel setText:[NSString stringWithFormat:@"%@ 公尺", [responseObject objectForKey:@"distance"]]];
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
                  failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
         ];
    }];
}

-(void)setState:(id)sender {
    BOOL state = [sender isOn];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"更新中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        NSMutableDictionary *data = [NSMutableDictionary new];
        [data setValue:[NSString stringWithFormat:@"%@", state ? @"1" : @"0"] forKey:@"is_enabled"];
        [data setValue:@"put" forKey:@"_method"];
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager POST:[NSString stringWithFormat:PUT_MARCH_API_URL, self.marchId]
              parameters:data
                 success:^(AFHTTPRequestOperation *operation, id responseObject) {
                     [alert dismissViewControllerAnimated:YES completion:nil];
                 }
                 failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                     [self.enableSwitchButton setOn:!self.enableSwitchButton.isOn animated:NO];
                     [alert dismissViewControllerAnimated:YES completion:nil];
                 }
         ];
    }];
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self setHidden];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager GET:[NSString stringWithFormat:LAST_API_URL, self.marchId]
              parameters:nil
                 success:^(AFHTTPRequestOperation *operation, id responseObject) {
                     [self setShow: responseObject];
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
//                                                   [self.navigationController popToRootViewControllerAnimated:YES];
                                                   [self.navigationController popViewControllerAnimated:YES];
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
