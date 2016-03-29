//
//  MapsViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>
#import "UIImageView+WebCache.h"
#import "Path.h"
#import "Header.h"
#import "AFHTTPRequestOperationManager.h"
@import GoogleMaps;

@interface MapsViewController : UIViewController <CLLocationManagerDelegate>

@property GMSMapView *mapView;
@property GMSMarker *mazu;
@property BOOL isLoading;
@property NSMutableArray<Path *> *paths;
@property GMSPolyline *path;
@property NSTimer *timer;
@property CLLocationManager *locationManager;

+ (CLLocationCoordinate2D) oriLocation;
@end
